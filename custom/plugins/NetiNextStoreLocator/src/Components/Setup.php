<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components;

use NetInventors\NetiNextStoreLocator\Components\Setup\FlowBuilder;
use NetInventors\NetiNextStoreLocator\Components\Setup\MailTemplate;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Setup
{
    final public const IMPORT_EXPORT_PROFILE_NAME     = 'StoreLocator';

    final public const OLD_IMPORT_EXPORT_PROFILE_NAME = 'StoreLocatorSW5';

    private readonly MailTemplate     $mailTemplateSetup;

    private readonly FlowBuilder      $flowBuilderSetup;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly InstallContext     $context
    ) {
        $this->mailTemplateSetup = new MailTemplate($container);
        $this->flowBuilderSetup  = new FlowBuilder($container, $context, $this->mailTemplateSetup);
    }

    public function installImportExportProfile(Context $context): void
    {
        /** @var EntityRepository $importExportProfileRepository */
        $importExportProfileRepository = $this->container->get('import_export_profile.repository');
        /** @var EntityRepository $importExportProfileTranslationRepository */
        $importExportProfileTranslationRepository =
            $this->container->get('import_export_profile_translation.repository');

        $importExportProfile            = [];
        $importExportProfileTranslation = [];

        $reflection  = new \ReflectionClass(self::class);
        $languageIds = $this->getLanguageIds($context);

        /**
         * @var string $constantValue
         */
        foreach ($reflection->getConstants() as $_ => $constantValue) {
            if ($this->isProfileInstalled($importExportProfileRepository, $context, $constantValue)) {
                continue;
            }
            $profileId = Uuid::randomHex();

            switch ($constantValue) {
                case self::IMPORT_EXPORT_PROFILE_NAME:
                    $importExportProfile[] = $this->createProfileMapping($profileId, $constantValue);
                    break;
                case self::OLD_IMPORT_EXPORT_PROFILE_NAME:
                    $importExportProfile[] = $this->createOldProfileMapping($profileId, $constantValue);
                    break;
            }

            foreach ($languageIds as $languageId) {
                $importExportProfileTranslation[] =
                    $this->createProfileTranslation($languageId, $profileId, $constantValue);
            }
        }

        if (!empty($importExportProfile)) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $importExportProfileRepository->create($importExportProfile, $context);
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $importExportProfileTranslationRepository->upsert($importExportProfileTranslation, $context);
        }
    }

    public function uninstallImportExportProfile(Context $context): void
    {
        /** @var EntityRepository $importExportProfileRepository */
        $importExportProfileRepository = $this->container->get('import_export_profile.repository');

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'StoreLocator'));
        $criteria->addFilter(new EqualsFilter('sourceEntity', 'neti_store_locator'));
        $profileIds = $importExportProfileRepository->searchIds($criteria, $context)->getIds();

        $profileIds = \array_map(
            fn ($profileId) => [ 'id' => $profileId ],
            $profileIds
        );

        if (0 < \count($profileIds)) {
            $importExportProfileRepository->delete($profileIds, $context);
        }
    }

    /**
     * @return string[]
     */
    private function getLanguageIds(Context $context): array
    {
        /** @var EntityRepository $languageRepository */
        $languageRepository = $this->container->get('language.repository');
        $languageCriteria   = new Criteria();

        /** @var string[] $ids */
        $ids = $languageRepository->searchIds($languageCriteria, $context)->getIds();

        return $ids;
    }

    private function isProfileInstalled(
        EntityRepository $importExportProfileRepository,
        Context          $context,
        string           $name
    ): bool {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));
        $criteria->addFilter(new EqualsFilter('sourceEntity', 'neti_store_locator'));

        $result = $importExportProfileRepository->searchIds($criteria, $context);

        return !(0 === $result->getTotal());
    }

    /**
     *
     * @return array|array[]
     */
    private function createProfileMapping(string $id, string $name): array
    {
        return [
            'id'            => $id,
            'name'          => $name,
            'label'         => $name,
            'systemDefault' => false,
            'sourceEntity'  => 'neti_store_locator',
            'fileType'      => 'text/csv',
            'delimiter'     => ';',
            'enclosure'     => '"',
            'mapping'       => [
                [
                    'key'       => 'id',
                    'mappedKey' => 'id',
                ],
                [
                    'key'       => 'label',
                    'mappedKey' => 'label',
                ],
                [
                    'key'       => 'street',
                    'mappedKey' => 'street',
                ],
                [
                    'key'       => 'streetNumber',
                    'mappedKey' => 'street_number',
                ],
                [
                    'key'       => 'zipCode',
                    'mappedKey' => 'zip_code',
                ],
                [
                    'key'       => 'city',
                    'mappedKey' => 'city',
                ],
                [
                    'key'       => 'countryId',
                    'mappedKey' => 'country_id',
                ],
                [
                    'key'       => 'longitude',
                    'mappedKey' => 'longitude',
                ],
                [
                    'key'       => 'latitude',
                    'mappedKey' => 'latitude',
                ],
                [
                    'key'       => 'phone',
                    'mappedKey' => 'phone',
                ],
                [
                    'key'       => 'fax',
                    'mappedKey' => 'fax',
                ],
                [
                    'key'       => 'url',
                    'mappedKey' => 'url',
                ],
                [
                    'key'       => 'email',
                    'mappedKey' => 'email',
                ],
                [
                    'key'       => 'countryId',
                    'mappedKey' => 'country_id',
                ],
                [
                    'key'       => 'country.iso',
                    'mappedKey' => 'country_iso',
                ],
                [
                    'key'       => 'active',
                    'mappedKey' => 'active',
                ],
                [
                    'key'       => 'contactFormEnabled',
                    'mappedKey' => 'contact_form_enabled',
                ],
                [
                    'key'       => 'hidden',
                    'mappedKey' => 'hidden',
                ],
                [
                    'key'       => 'notificationMailAddress',
                    'mappedKey' => 'notification_email',
                ],
                [
                    'key'       => 'showAlways',
                    'mappedKey' => 'show_always',
                ],
                [
                    'key'       => 'zoom',
                    'mappedKey' => 'zoom',
                ],
                [
                    'key'       => 'excludeFromSync',
                    'mappedKey' => 'exclude_from_sync',
                ],
                [
                    'key'       => 'googlePlaceID',
                    'mappedKey' => 'google_place_id',
                ],
                [
                    'key'       => 'featured',
                    'mappedKey' => 'featured',
                ],
                [
                    'key'       => 'radius',
                    'mappedKey' => 'radius',
                ],
                [
                    'key'       => 'detailPageEnabled',
                    'mappedKey' => 'detail_page_enabled',
                ],
                [
                    'key'       => 'pictureMediaId',
                    'mappedKey' => 'picture_media_id',
                ],
                [
                    'key'       => 'iconMediaId',
                    'mappedKey' => 'icon_media_id',
                ],
                [
                    'key'       => 'detailContentType',
                    'mappedKey' => 'detail_content_type',
                ],
                [
                    'key'       => 'cmsPageId',
                    'mappedKey' => 'cms_page_id',
                ],
                [
                    'key'       => 'contactFormDetail',
                    'mappedKey' => 'contact_form_detail',
                ],
                [
                    'key'       => 'salesChannels',
                    'mappedKey' => 'sales_channels',
                ],
                [
                    'key'       => 'countryState.shortCode',
                    'mappedKey' => 'country_state_short_code',
                ],
                [
                    'key'       => 'countryStateId',
                    'mappedKey' => 'country_state_id',
                ],
                [
                    'key'       => 'tags',
                    'mappedKey' => 'tags',
                ],
                [
                    'key'       => 'translations.DEFAULT.seoDescription',
                    'mappedKey' => 'seo_description',
                ],
                [
                    'key'       => 'translations.DEFAULT.seoTitle',
                    'mappedKey' => 'seo_title',
                ],
                [
                    'key'       => 'translations.DEFAULT.seoUrl',
                    'mappedKey' => 'seo_url',
                ],
                [
                    'key'       => 'translations.DEFAULT.additionalInformation',
                    'mappedKey' => 'additional_information',
                ],
                [
                    'key'       => 'translations.DEFAULT.description',
                    'mappedKey' => 'description',
                ],
                [
                    'key'       => 'detailContentType',
                    'mappedKey' => 'detail_content_type',
                ],
                [
                    'key'       => 'translations.DEFAULT.detailDescription',
                    'mappedKey' => 'detail_description',
                ],
                [
                    'key'       => 'translations.DEFAULT.detailTitle',
                    'mappedKey' => 'detail_title',
                ],
            ],
        ];
    }

    /**
     *
     * @return array|array[]
     */
    private function createOldProfileMapping(string $id, string $name): array
    {
        return [
            'id'            => $id,
            'name'          => $name,
            'label'         => $name,
            'systemDefault' => false,
            'sourceEntity'  => 'neti_store_locator',
            'fileType'      => 'text/csv',
            'delimiter'     => ',',
            'enclosure'     => '"',
            'mapping'       => [
                [
                    'key'       => 'id',
                    'mappedKey' => 'id',
                ],
                [
                    'key'       => 'salesChannels',
                    'mappedKey' => 'subshopIDs',
                ],
                [
                    'key'       => 'label',
                    'mappedKey' => 'name',
                ],
                [
                    'key'       => 'street',
                    'mappedKey' => 'street',
                ],
                [
                    'key'       => 'streetNumber',
                    'mappedKey' => 'number',
                ],
                [
                    'key'       => 'zipCode',
                    'mappedKey' => 'zip',
                ],
                [
                    'key'       => 'city',
                    'mappedKey' => 'city',
                ],
                [
                    'key'       => 'countryId',
                    'mappedKey' => 'countryId',
                ],
                [
                    'key'       => 'longitude',
                    'mappedKey' => 'lng',
                ],
                [
                    'key'       => 'latitude',
                    'mappedKey' => 'lat',
                ],
                [
                    'key'       => 'phone',
                    'mappedKey' => 'phone',
                ],
                [
                    'key'       => 'fax',
                    'mappedKey' => 'fax',
                ],
                [
                    'key'       => 'url',
                    'mappedKey' => 'url',
                ],
                [
                    'key'       => 'email',
                    'mappedKey' => 'email',
                ],
                [
                    'key'       => 'countryId',
                    'mappedKey' => 'country_id',
                ],
                [
                    'key'       => 'active',
                    'mappedKey' => 'active',
                ],
                [
                    'key'       => 'contactFormEnabled',
                    'mappedKey' => 'contact',
                ],
                [
                    'key'       => 'hidden',
                    'mappedKey' => 'hideStore',
                ],
                [
                    'key'       => 'notificationMailAddress',
                    'mappedKey' => 'notifyemail',
                ],
                [
                    'key'       => 'showAlways',
                    'mappedKey' => 'alwaysdisplay',
                ],
                [
                    'key'       => 'zoom',
                    'mappedKey' => 'zoom',
                ],
                [
                    'key'       => 'googlePlaceID',
                    'mappedKey' => 'placeId',
                ],
                [
                    'key'       => 'featured',
                    'mappedKey' => 'featured',
                ],
                [
                    'key'       => 'radius',
                    'mappedKey' => 'radius',
                ],
                [
                    'key'       => 'detailPageEnabled',
                    'mappedKey' => 'detailPageEnabled',
                ],
                [
                    'key'       => 'externalId',
                    'mappedKey' => 'external_id',
                ],
            ],
        ];
    }

    private function createProfileTranslation(string $languageId, string $profileId, string $profileName): array
    {
        return [
            'importExportProfileId' => $profileId,
            'languageId'            => $languageId,
            'label'                 => $profileName,
        ];
    }

    public function install(Context $context): void
    {
        $this->installImportExportProfile($context);
        $this->mailTemplateSetup->create($context);

        $this->flowBuilderSetup->createFlows();
    }

    public function update(Context $context): void
    {
        $this->installImportExportProfile($context);
        $this->mailTemplateSetup->create($context);
    }

    public function uninstall(Context $context): void
    {
        $this->flowBuilderSetup->deleteFlows();
        $this->mailTemplateSetup->remove($context);
    }
}
