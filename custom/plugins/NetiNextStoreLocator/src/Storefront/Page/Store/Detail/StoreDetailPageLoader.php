<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail;

use NetInventors\NetiNextStoreLocator\Components\CmsPageRenderer;
use NetInventors\NetiNextStoreLocator\Components\ContactForm\ContactForm;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreCms\StoreCmsCollection;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreCms\StoreCmsEntity;
use NetInventors\NetiNextStoreLocator\Service\StoreBusinessHoursService;
use NetInventors\NetiNextStoreLocator\Service\StorefrontConfigFactory;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\Routing\RoutingException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Storefront\Page\MetaInformation;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreDetailPageLoader
{
    public function __construct(
        private readonly GenericPageLoaderInterface        $genericLoader,
        private readonly StoreDetailLoader                 $storeDetailLoader,
        private readonly ContactForm                       $contactForm,
        private readonly CmsPageRenderer                   $cmsPageRenderer,
        private readonly EventDispatcherInterface          $eventDispatcher,
        private readonly SeoUrlPlaceholderHandlerInterface $seoUrlReplacer,
        private readonly StoreBusinessHoursService         $businessHoursService,
        private readonly StorefrontConfigFactory           $storefrontConfigFactory
    ) {
    }

    public function load(Request $request, SalesChannelContext $context): StoreDetailPage
    {
        $page = $this->genericLoader->load($request, $context);
        $page = StoreDetailPage::createFrom($page);

        /** @var string|null $storeId */
        $storeId = $request->attributes->get('id');

        if (!$storeId) {
            throw RoutingException::missingRequestParameter('id', '/id');
        }

        $store = $this->storeDetailLoader->load($storeId, $context);
        $page->setStore($store);

        $config = $this->storefrontConfigFactory->getConfig($context);
        $page->setConfig($config);

        $translated = $store->getTranslated();

        $metaInfoInformation = $page->getMetaInformation();

        if ($metaInfoInformation instanceof MetaInformation) {
            $metaInfoInformation->setMetaTitle((string) ($translated['seoTitle'] ?? $store->getLabel()));

            if (!\is_string($translated['seoDescription'])) {
                $translated['seoDescription'] = '';
            }

            $metaInfoInformation->setMetaDescription($translated['seoDescription']);
            $metaInfoInformation->assign(
                [
                    'canonical' => $this->seoUrlReplacer->generate('frontend.store_locator.detail', [ 'id' => $storeId ]),
                ]
            );
        }

        $contactFormFields = $this->contactForm->getFields($context);
        $page->setContactFormFields($contactFormFields);

        $contactSubjectOptions = StorefrontConfigFactory::getContactSubjectOptions($config);
        $page->setContactSubjectOptions($contactSubjectOptions);

        $page->setStoreBusinessHours(
            $this->businessHoursService->getStoreBusinessHours($storeId, $context->getContext())
        );
        $page->setWeekDays($this->businessHoursService->getBusinessWeekdays($context->getContext()));

        $timeZone = $store->getTimezone() ?: $request->cookies->get('timezone');

        if (\is_string($timeZone)) {
            $time = new \DateTime(
                'now',
                new \DateTimeZone($timeZone)
            );

            $page->setIsStoreOpen($this->businessHoursService->isStoreOpen($time, $storeId, $context->getContext()));
        }

        if (!\is_string($translated['detailDescription'])) {
            $translated['detailDescription'] = '';
        }

        switch ($store->getDetailContentType()) {
            case StoreEntity::CONTENT_TYPE_HTML:
                $page->setHtmlContent(
                    $translated['detailDescription']
                );
                break;
            case StoreEntity::CONTENT_TYPE_CMS:
                $page->setHtmlContent($this->buildCmsContent($store, $request, $context));
                break;
        }

        $this->eventDispatcher->dispatch(new StoreDetailPageLoadedEvent($page, $context, $request));

        return $page;
    }

    private function buildCmsContent(StoreEntity $store, Request $request, SalesChannelContext $context): ?string
    {
        $html     = '';
        $cmsPages = $store->getCmsPages();

        if (!$cmsPages instanceof StoreCmsCollection) {
            return null;
        }

        $cmsPages->sort(
            static fn (StoreCmsEntity $a, StoreCmsEntity $b) => $a->getPosition() - $b->getPosition()
        );

        /** @var StoreCmsEntity $cmsStoreElement */
        foreach ($cmsPages->getElements() as $cmsStoreElement) {
            $cmsPage = $cmsStoreElement->getCmsPage();

            if (!$cmsPage instanceof CmsPageEntity) {
                continue;
            }

            $cmsContent = $this->cmsPageRenderer->build(
                $request,
                $context,
                $cmsPage
            );

            $html .= $cmsContent;
        }

        return $html;
    }
}
