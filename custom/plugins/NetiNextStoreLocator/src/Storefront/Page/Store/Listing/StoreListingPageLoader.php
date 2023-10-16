<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use NetInventors\NetiNextStoreLocator\Components\CmsPageRenderer;
use NetInventors\NetiNextStoreLocator\Components\ContactForm\ContactForm;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Service\StoreFilterService;
use NetInventors\NetiNextStoreLocator\Service\StorefrontConfigFactory;
use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use NetInventors\NetiNextStoreLocator\Struct\StoreSelectState;
use NetInventors\NetiNextStorePickup\Service\ContextService;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\RepositoryIterator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Grouping\FieldGrouping;
use Shopware\Core\Framework\Plugin\PluginEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Storefront\Page\MetaInformation;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @psalm-suppress UndefinedClass The class is only available when StorePickup is installed.
 *
 * @psalm-type StoreListingCountry = array{
 *     id: string,
 *     label: string,
 *     _label: string,
 *     isoCode: string,
 *     default: bool,
 *     position: int
 * }
 */
class StoreListingPageLoader
{
    /**
     * @psalm-suppress UndefinedClass The class ContextService is only available when StorePickup is installed.
     */
    public function __construct(
        private readonly GenericPageLoaderInterface        $genericLoader,
        private readonly EntityRepository                  $storeRepository,
        private readonly ContactForm                       $contactForm,
        private readonly CmsPageRenderer                   $cmsPageRenderer,
        private readonly EventDispatcherInterface          $eventDispatcher,
        private readonly TranslatorInterface               $translator,
        private readonly EntityRepository                  $pluginRepository,
        private readonly StoreFilterService                $storeFilterService,
        private readonly ?ContextService                   $contextService,
        private readonly SeoUrlPlaceholderHandlerInterface $seoUrlReplacer,
        private readonly Connection                        $db,
        private readonly PluginConfigStruct                $pluginConfig,
        private readonly StorefrontConfigFactory           $storefrontConfigFactory
    ) {
    }

    public function load(Request $request, SalesChannelContext $context): StoreListingPage
    {
        $page = $this->genericLoader->load($request, $context);
        $page = StoreListingPage::createFrom($page);

        $meta = $page->getMetaInformation();

        /** @var array<string, string> $config */
        $config = $this->getConfig($context);
        $page->setConfig($config);

        if ($meta instanceof MetaInformation) {
            $meta->setMetaTitle(
                $this->translator->trans('neti-next-store-locator.index.title')
            );

            $meta->setMetaDescription(
                $this->translator->trans('neti-next-store-locator.index.description')
            );

            $seoUrl = $config['seoUrl'];

            if ('' !== $seoUrl) {
                $storefrontUrl = (string) $request->attributes->get('sw-storefront-url');
                $meta->assign([ 'canonical' => $storefrontUrl . '/' . $config['seoUrl'] ]);
            } else {
                $meta->assign([ 'canonical' => $this->seoUrlReplacer->generate('frontend.store_locator.index') ]);
            }
        }

        $countries = $this->getCountries($context, $config);
        $page->setCountries($countries);

        $filters = $this->storeFilterService->loadFiltersForStorefront($context);
        $page->setFilters($filters);

        $radiusList = $this->getRadiusList();
        $page->setRadiusList($radiusList);

        $contactFormFields = $this->contactForm->getFields($context);
        $page->setContactFormFields($contactFormFields);

        $contactSubjectOptions = StorefrontConfigFactory::getContactSubjectOptions($config);
        $page->setContactSubjectOptions($contactSubjectOptions);

        $page->setOrderTypes(
            [
                'distance',
                'country',
                'name',
                'random',
            ]
        );

        if (isset($config['topCmsPage']) && '' !== $config['topCmsPage']) {
            $page->setTopCmsPageHtml(
                $this->cmsPageRenderer->buildById($request, $context, $config['topCmsPage'], compact('page'))
            );
        }

        if (isset($config['bottomCmsPage']) && '' !== $config['bottomCmsPage']) {
            $page->setBottomCmsPageHtml(
                $this->cmsPageRenderer->buildById($request, $context, $config['bottomCmsPage'], compact('page'))
            );
        }

        $this->eventDispatcher->dispatch(new StoreListingPageLoadedEvent($page, $context, $request));

        return $page;
    }

    /**
     * Returns a list of all used countries
     *
     * @throws InconsistentCriteriaIdsException
     */
    public function getCountries(SalesChannelContext $context, array $config): array
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addGroupField(new FieldGrouping('countryId'));

        $result    = $this->storeRepository->search($criteria, $context->getContext());
        /**
         * @psalm-var StoreListingCountry[] $countries
         */
        $countries = [
            [
                'id'       => 'all',
                'label'    => $this->translator->trans('neti-next-store-locator.index.search.allCountriesLabel'),
                '_label'   => $this->translator->trans('neti-next-store-locator.index.search.allCountriesLabel'),
                'isoCode'  => 'ALL',
                'default'  => false,
                'position' => -1,
            ],
        ];

        $workingIndex         = 1;
        $defaultByConfigIndex = null;

        $umlautInput  = [ 'ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü' ];
        $umlautOutput = [ 'ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue' ];

        /** @var StoreEntity $entity */
        foreach ($result as $entity) {
            $country = $entity->getCountry();

            if (!($country instanceof CountryEntity)) {
                continue;
            }

            if (null === $defaultByConfigIndex && $country->getId() === ($config['preselectedCountryId'] ?? null)) {
                $defaultByConfigIndex = $workingIndex;
            }

            $countryName = (string) $country->getTranslation('name');

            /** @psalm-var StoreListingCountry $countryRow */
            $countryRow = [
                'id'       => $country->getId(),
                'label'    => $countryName,
                '_label'   => str_replace($umlautInput, $umlautOutput, $countryName),
                'isoCode'  => $country->getIso(),
                'default'  => false,
                'position' => $country->getPosition(),
            ];

            $countries[$workingIndex++] = $countryRow;
        }

        $defaultIndex = $defaultByConfigIndex ?? 0;

        $countries[$defaultIndex]['default'] = true;

        $sortBy = (string) ($config['countrySortBy'] ?? PluginConfigStruct::COUNTRY_SORT_BY_NAME_ASC);

        usort(
            $countries,
            /**
             * @psalm-param StoreListingCountry $a
             * @psalm-param StoreListingCountry $b
             */
            static function (array $a, array $b) use ($sortBy) {
                if ($a['id'] === 'all') {
                    return -1;
                }

                switch ($sortBy) {
                    case PluginConfigStruct::COUNTRY_SORT_BY_NAME_ASC:
                        return $a['_label'] > $b['_label'] ? 1 : -1;
                    case PluginConfigStruct::COUNTRY_SORT_BY_NAME_DESC:
                        return $a['_label'] < $b['_label'] ? 1 : -1;
                    case PluginConfigStruct::COUNTRY_SORT_BY_POSITION_ASC:
                        return $a['position'] > $b['position'] ? 1 : -1;
                    case PluginConfigStruct::COUNTRY_SORT_BY_POSITION_DESC:
                        return $a['position'] < $b['position'] ? 1 : -1;
                }

                return 0;
            }
        );

        return $countries;
    }

    /**
     * @param Request             $request
     * @param SalesChannelContext $context
     *
     * @return EntitySearchResult
     * @throws Exception
     */
    public function getStores(Request $request, SalesChannelContext $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addAssociation('countryState');
        $criteria->addAssociation('pictureMedia');
        $criteria->addAssociation('iconMedia');
        $criteria->addAssociation('translation');
        $criteria->addAssociation('tags');
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addFilter(new EqualsFilter('salesChannels.id', $context->getSalesChannelId()));
        $criteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_OR,
                [
                    new EqualsFilter('latitude', null),
                    new EqualsFilter('longitude', null),
                ]
            )
        );

        if ($request->query->has('radius')) {
            $radius    = $request->query->get('radius');
            $longitude = $request->query->get('lng');
            $latitude  = $request->query->get('lat');
            $radiant   = ('km' === $this->pluginConfig->getDistanceUnit()) ? 6371 : 3959;
            $limit     = $this->pluginConfig->getSearchResultLimit();

            $sql = '
                SELECT
                  LOWER(HEX(s.id)),
                  ( :radiant
                    * ACOS(
                      COS(RADIANS(:latitude))
                      * COS(RADIANS(s.latitude))
                      * COS(RADIANS(s.longitude) - RADIANS(:longitude))
                      + SIN(RADIANS(:latitude))
                      * SIN(RADIANS(s.latitude))
                  )) AS distance
                FROM neti_store_locator s
                LEFT JOIN neti_store_sales_channel ssc ON ssc.store_id = s.id
                WHERE s.active = 1
                  AND s.latitude IS NOT NULL
                  AND s.longitude IS NOT NULL
                  AND ssc.sales_channel_id = :salesChannelId
                HAVING distance < :radius
                ORDER BY distance ASC
                LIMIT :limit
            ';

            $sql = str_replace(':limit', (string) $limit, $sql);

            /** @var string[] $ids */
            $ids = $this->db->fetchFirstColumn($sql, [
                'salesChannelId' => Uuid::fromHexToBytes($context->getSalesChannelId()),
                'radiant'        => $radiant,
                'longitude'      => $longitude,
                'latitude'       => $latitude,
                'radius'         => $radius,
            ]);

            $criteria->addFilter(new EqualsAnyFilter('id', $ids));
        }

        $iterator = new RepositoryIterator($this->storeRepository, $context->getContext(), $criteria);
        $result   = null;

        if (!$iterator->getTotal()) {
            return new EntitySearchResult(
                StoreDefinition::ENTITY_NAME,
                0,
                new EntityCollection(),
                null,
                $criteria,
                $context->getContext()
            );
        }

        while ($rows = $iterator->fetch()) {
            if (null === $result) {
                $result = $rows;
            } else {
                foreach ($rows as $row) {
                    $result->add($row);
                }
            }
        }

        if (null === $result) {
            throw new \Exception('No stores loaded.');
        }

        $detailMode      = $this->pluginConfig->isDetailPage();
        $selectedStoreId = null;

        if (
            null !== $this->contextService
            && $this->isStorePickupEnabled($context->getContext())
        ) {
            /**
             * @psalm-suppress UndefinedClass The class is only available when StorePickup is installed.
             * @var string|null $selectedStoreId
             */
            $selectedStoreId = $this->contextService->getSelectedStore($request->getSession());
        }

        /** @var StoreEntity $entity */
        foreach ($result as $entity) {
            if ($entity->getId() === $selectedStoreId) {
                $entity->addExtension('netiStorePickupSelected', new StoreSelectState());
            }

            switch ($detailMode) {
                case 'enabled':
                    $entity->setDetailPageEnabled(true);
                    break;
                case 'disabled':
                    $entity->setDetailPageEnabled(false);
                    break;
                case 'store':
                    // Keep value
                    break;
            }
        }

        return $result;
    }

    private function getRadiusList(): array
    {
        $values       = trim($this->pluginConfig->getSearchRadiusValues());
        $defaultValue = $this->pluginConfig->getDefaultSearchRadius();

        if (empty($values)) {
            return [];
        }

        $values = array_unique(explode(';', trim($values, '; ')));

        return array_map(
            static fn ($value) => [
                'default' => (int) $value === $defaultValue,
                'value'   => $value,
            ],
            $values
        );
    }

    public function getConfig(SalesChannelContext $context): array
    {
        return $this->storefrontConfigFactory->getConfig($context);
    }

    private function isStorePickupEnabled(Context $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'NetiNextStorePickup'));

        $result = $this->pluginRepository->search($criteria, $context);
        /** @var PluginEntity|null $plugin */
        $plugin = $result->first();

        return $plugin instanceof PluginEntity
            && $plugin->getInstalledAt() !== null
            && $plugin->getActive() === true;
    }
}
