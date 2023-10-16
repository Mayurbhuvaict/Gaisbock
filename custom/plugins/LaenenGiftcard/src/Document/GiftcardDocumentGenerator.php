<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Document;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Laenen\Giftcard\Event\GiftcardTemplateConfigEvent;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Shopware\Core\Checkout\Document\DocumentConfigurationFactory;
use Shopware\Core\Checkout\Document\Renderer\RenderedDocument;
use Shopware\Core\Checkout\Document\Service\PdfRenderer;
use Shopware\Core\Checkout\Document\Twig\DocumentTemplateRenderer;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GiftcardDocumentGenerator
{
    public function __construct(
        private DocumentTemplateRenderer $documentTemplateRenderer,
        private PdfRenderer $pdfRenderer,
        private SystemConfigService $systemConfigService,
        private EntityRepository $mediaRepository,
        private EntityRepository $productRepository,
        private EntityRepository $languageRepository,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    public function generate(?string $filename, GiftcardStruct $giftcard, SalesChannelContext $context): string
    {
        $config = DocumentConfigurationFactory::createConfiguration([], null);

        $config->setPageOrientation('portrait');
        $config->setPageSize('a4');
        $config->setDocumentNumber('giftcard_' . $giftcard->getCode());
        $config->assign(['displayFooter' => false, 'displayHeader' => false, 'itemsPerPage' => 1]);
        $config->__set('logo', $this->getLogo($context));

        /** @var GiftcardEntity $giftcardEntity */
        $giftcardEntity = $giftcard->getExtension('entity');

        $giftcardProduct = $this->productRepository->search(
            new Criteria([$giftcardEntity->getOriginProductId()]),
            $context->getContext()
        )->first();

        $templateConfig = [];

        if (!$giftcardProduct instanceof ProductEntity) {
            $giftcardTemplate = 'simple';
        } else {
            $customFields = $giftcardProduct->getTranslation('customFields');
            $giftcardTemplate = $customFields['laeGiftcardDesignType'] ?? 'simple';

            $event = new GiftcardTemplateConfigEvent($giftcardEntity, $giftcardTemplate, $giftcardProduct, $context);
            $this->dispatcher->dispatch($event);

            $templateConfig = $event->getConfig();
        }

        $html = $this->documentTemplateRenderer->render(
            '@Storefront/documents/lae-giftcard-' . $giftcardTemplate . '.html.twig',
            [
                'config' => $config,
                'context' => $context,
                'giftcard' => $giftcard,
                'templateConfig' => $templateConfig,
            ],
            $context->getContext(),
            $context->getSalesChannelId(),
            $context->getLanguageId(),
            $this->getLocale($context)
        );

        $renderedDocument = new RenderedDocument($html, '', $filename ?? 'giftcard');

        return $this->pdfRenderer->render($renderedDocument);
    }

    private function getLogo(SalesChannelContext $context): ?MediaEntity
    {
        $mediaId = $this->systemConfigService->get(
            'LaenenGiftcard.config.giftcardPdfLogo',
            $context->getSalesChannelId()
        );

        if (empty($mediaId)) {
            return null;
        }

        return $this->mediaRepository->search(new Criteria([$mediaId]), $context->getContext())->first();
    }

    private function getLocale(SalesChannelContext $context): string
    {
        $criteria = new Criteria([$context->getLanguageId()]);
        $criteria->addAssociation('locale');
        $language = $this->languageRepository->search($criteria, $context->getContext())->first();

        if (!$language instanceof LanguageEntity || !$language->getLocale()) {
            return 'en-GB';
        }

        return $language->getLocale()->getCode();
    }
}
