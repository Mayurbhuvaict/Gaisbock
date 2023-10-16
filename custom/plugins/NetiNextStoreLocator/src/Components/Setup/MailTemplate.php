<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\Setup;

use NetInventors\NetiNextStoreLocator\Components\Mail\Mails;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Locale\LocaleEntity;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function is_array;
use function is_string;

/**
 * @psalm-type MailTemplateConfigurationType = array{
 *     name: string,
 *     technicalName: string,
 *     description: array<string, string>,
 *     subject: array<string, string>,
 *     availableEntities: array<string, string>,
 *     senderName: string
 * }
 */
class MailTemplate
{
    protected EntityRepository $mailTemplateTypeRepository;

    protected EntityRepository $mailTemplateRepository;

    protected EntityRepository $languageRepository;

    public function __construct(
        ContainerInterface $container
    ) {
        /** @var EntityRepository mailTemplateTypeRepository */
        $this->mailTemplateTypeRepository = $container->get('mail_template_type.repository');

        /** @var EntityRepository mailTemplateRepository */
        $this->mailTemplateRepository     = $container->get('mail_template.repository');

        /** @var EntityRepository languageRepository */
        $this->languageRepository         = $container->get('language.repository');
    }

    /**
     * @param list<string> $technicalNames
     *
     * @return array<string, MailTemplateEntity>
     */
    public function getMailTemplates(array $technicalNames, Context $context): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsAnyFilter('mailTemplateType.technicalName', $technicalNames)
        )
            ->addAssociation('mailTemplateType');

        $mailTemplates = $this->mailTemplateRepository->search($criteria, $context);
        if ($mailTemplates->count() < \count($technicalNames)) {
            throw new \RuntimeException(
                \sprintf(
                    'Required mail template %s not found.',
                    \implode(', ', $technicalNames)
                )
            );
        }

        $result = [];

        /** @var MailTemplateEntity $mailTemplate */
        foreach ($mailTemplates->getElements() as $mailTemplate) {
            /** @var MailTemplateTypeEntity $mailTemplateType */
            $mailTemplateType                              = $mailTemplate->getMailTemplateType();
            $result[$mailTemplateType->getTechnicalName()] = $mailTemplate;
        }

        return $result;
    }

    public function create(Context $context): void
    {
        $mailTemplateTypes = [];
        $mailTemplates     = [];
        $languages         = $this->getLanguages($context);

        /** @var LanguageEntity $defaultLanguage */
        $defaultLanguage = $languages->get(Defaults::LANGUAGE_SYSTEM);
        $defaultLocale   = $defaultLanguage->getLocale();

        if ($defaultLocale instanceof LocaleEntity) {
            $defaultIsoCode  = $defaultLocale->getCode();
        } else {
            $defaultIsoCode = 'en-GB';
        }

        /**
         * This must be extended when we support more than 2 languages.
         *
         * The $languageCode is only used to read the mailConfig.subject and mailConfig.description correctly.
         */
        $languageCode = $defaultIsoCode === 'de-DE' ? $defaultIsoCode : 'en-GB';

        foreach ($this->filterExistingMailConfigs($this->getMails(), $context) as $mailConfig) {
            $templatePath = __DIR__ . '/../Mail/' . $mailConfig['technicalName'] . '/';

            $mailTemplateType = [
                'id'                => Uuid::randomHex(),
                'name'              => $mailConfig['name'],
                'technicalName'     => $mailConfig['technicalName'],
                'availableEntities' => $mailConfig['availableEntities'] ?? [],
            ];

            $mailTemplateTypes[] = $mailTemplateType;

            $mailTemplate = [
                'id'                 => Uuid::randomHex(),
                'mailTemplateTypeId' => $mailTemplateType['id'],
                'subject'            => $mailConfig['subject'][$languageCode],
                'contentPlain'       => $this->loadContent($templatePath, $defaultIsoCode, 'plain.html.twig'),
                'contentHtml'        => $this->loadContent($templatePath, $defaultIsoCode, 'html.html.twig'),
                'senderName'         => $mailConfig['senderName'] ?? '{{ salesChannel.name }}',
                'description'        => $mailConfig['description'][$languageCode],
                'translations'       => [],
            ];

            /** @var LanguageEntity $language */
            foreach ($languages->getElements() as $language) {
                $locale = $language->getLocale();

                if (
                    !$locale instanceof LocaleEntity
                    || $defaultIsoCode === $locale->getCode()
                ) {
                    continue;
                }

                $isoCode = $locale->getCode();

                $mailTemplate['translations'][] = [
                    'id'                 => Uuid::randomHex(),
                    'mailTemplateTypeId' => $mailTemplateType['id'],
                    'subject'            => $mailConfig['subject'][$isoCode],
                    'contentPlain'       => $this->loadContent($templatePath, $isoCode, 'plain.html.twig'),
                    'contentHtml'        => $this->loadContent($templatePath, $isoCode, 'html.html.twig'),
                    'senderName'         => $mailConfig['senderName'] ?? '{{ salesChannel.name }}',
                    'description'        => $mailConfig['description'][$isoCode],
                    'languageId'         => $language->getId(),
                ];
            }

            $mailTemplates[] = $mailTemplate;
        }

        $this->mailTemplateTypeRepository->create($mailTemplateTypes, $context);
        $this->mailTemplateRepository->create($mailTemplates, $context);
    }

    public function remove(Context $context): void
    {
        $technicalMailNames = [];

        foreach ($this->getMails() as $mailConfig) {
            $technicalMailNames[] = $mailConfig['technicalName'];
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('technicalName', $technicalMailNames));

        $mailTemplateTypeIds = $this->mailTemplateTypeRepository->search($criteria, $context)->getIds();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('mailTemplateTypeId', $mailTemplateTypeIds));

        $mailTemplateIds = $this->mailTemplateRepository->searchIds($criteria, $context)->getIds();

        $this->removeIds($this->mailTemplateRepository, $mailTemplateIds, $context);
        $this->removeIds($this->mailTemplateTypeRepository, $mailTemplateTypeIds, $context);
    }

    /**
     * A helper method to delete a bunch of ids of the given repository.
     */
    private function removeIds(EntityRepository $repository, array $ids, Context $context): void
    {
        $ids = \array_map(
            static fn ($id) => [ 'id' => $id ],
            $ids
        );

        if (0 < \count($ids)) {
            $repository->delete(\array_values($ids), $context);
        }
    }

    /**
     * Extracts the mail template definitions from the \NetInventors\NetiNextEasyCoupon\Components\Mail\Mails class
     *
     * @psalm-return array<string, MailTemplateConfigurationType>
     */
    private function getMails(): array
    {
        $mailTemplateConfigurations = [];
        $constants                  = (new \ReflectionClass(Mails::class))->getConstants();

        /** @var mixed $value */
        foreach ($constants as $key => $value) {
            if ($this->validateMailArraySchema($value)) {
                /** @psalm-var MailTemplateConfigurationType $value */
                $mailTemplateConfigurations[$key] = $value;
            }
        }

        return $mailTemplateConfigurations;
    }

    private function validateMailArraySchema(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        $allKeysAvailable = isset(
            $value['name'],
            $value['technicalName'],
            $value['description'],
            $value['subject'],
            $value['availableEntities'],
            $value['senderName']
        );

        if (
            !$allKeysAvailable
            || !is_string($value['name'])
            || !is_string($value['technicalName'])
            || !is_string($value['senderName'])
            || !is_array($value['description'])
            || !is_array($value['subject'])
            || !is_array($value['availableEntities'])
        ) {
            return false;
        }

        /** @var mixed $var */
        foreach ($value['description'] as $key => $var) {
            if (!(is_string($key) && is_string($var))) {
                return false;
            }
        }

        /** @var mixed $var */
        foreach ($value['subject'] as $key => $var) {
            if (!(is_string($key) && is_string($var))) {
                return false;
            }
        }

        /** @var mixed $var */
        foreach ($value['availableEntities'] as $key => $var) {
            if (!(is_string($key) && is_string($var))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Loads the content for the given locale and type. If the given locale does not exist, we fallback to
     * $fallbackIsoCode
     */
    private function loadContent(string $path, string $isoCode, string $type, string $fallbackIsoCode = 'en-GB'): string
    {
        $filename = $path . $isoCode . '/' . $type;

        if (!is_file($filename)) {
            $filename = $path . $fallbackIsoCode . '/' . $type;
        }

        return file_get_contents($filename);
    }

    /**
     * Load languages.
     */
    private function getLanguages(Context $context): EntityCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('locale');

        $criteria->addFilter(
            new MultiFilter(
                MultiFilter::CONNECTION_OR,
                [
                    new EqualsFilter('id', Defaults::LANGUAGE_SYSTEM),
                    new EqualsAnyFilter('locale.code', [ 'en-GB', 'de-DE' ]),
                ]
            )
        );

        return $this->languageRepository->search($criteria, $context);
    }

    /**
     * @psalm-param array<string, MailTemplateConfigurationType> $mailConfigs
     *
     * @psalm-return array<string, MailTemplateConfigurationType>
     */
    private function filterExistingMailConfigs(array $mailConfigs, Context $context): array
    {
        $technicalNamesKeys = [];

        foreach ($mailConfigs as $key => $mailConfig) {
            $technicalNamesKeys[$mailConfig['technicalName']] = $key;
        }

        if ([] === $technicalNamesKeys) {
            return $mailConfigs;
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('technicalName', \array_keys($technicalNamesKeys)));
        $mailTemplateTypes = $this->mailTemplateTypeRepository->search($criteria, $context);

        /** @var MailTemplateTypeEntity $mailTemplateType */
        foreach ($mailTemplateTypes->getEntities()->getElements() as $mailTemplateType) {
            $technicalName = $mailTemplateType->getTechnicalName();
            $key           = $technicalNamesKeys[$technicalName];

            unset($mailConfigs[$key]);
        }

        return $mailConfigs;
    }
}
