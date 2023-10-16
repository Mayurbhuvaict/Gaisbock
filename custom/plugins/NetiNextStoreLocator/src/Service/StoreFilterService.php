<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue\FilterValueEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterCollection;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\CustomField\CustomFieldEntity;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Locale\LocaleEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\Translation\TranslatorInterface;

class StoreFilterService
{
    final public const CUSTOM_FIELD_TYPE_WHITELIST = [
        'select',
        'text',
        'color',
        'date',
        'number',
        'bool',
    ];

    /**
     * @var array<string, string>
     */
    private array $languageCodeCache = [];

    public function __construct(
        private readonly EntityRepository $filterRepository,
        private readonly EntityRepository $languageRepository,
        private readonly TranslatorInterface       $translator,
        private readonly StoreFilterValueService   $valueService
    ) {
    }

    public function build(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true));

        /** @var FilterEntity[] $filters */
        $filters = $this->filterRepository->search($criteria, $context)->getElements();

        foreach ($filters as $filter) {
            $this->valueService->build($filter->getId(), $context);
        }
    }

    public function loadFiltersForStorefront(
        SalesChannelContext $salesChannelContext
    ): array {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addSorting(new FieldSorting('position'));

        $criteria->addAssociation('tags');
        $criteria->addAssociation('customField');
        $criteria->addAssociation('values');

        /** @var FilterCollection $filters */
        $filters = $this->filterRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
        $result  = [];

        /** @var FilterEntity $filter */
        foreach ($filters as $filter) {
            $item = [
                'id'          => $filter->getId(),
                'title'       => $filter->getTranslation('title'),
                'displayType' => $filter->getDisplayType(),
                'valueType'   => $filter->getValueType(),
                'values'      => [],
            ];

            if ($filter->getValueType() === FilterEntity::VALUE_TYPE_TAG) {
                foreach ($filter->getTags() as $tag) {
                    $item['values'][] = [
                        'inputId' => $filter->getId() . '_' . $tag->getId(),
                        'id'      => $tag->getId(),
                        'label'   => $tag->getName(),
                    ];
                }
            } elseif (
                $filter->getValueType() === FilterEntity::VALUE_TYPE_CUSTOM_FIELD
                && null !== $filter->getCustomField()
            ) {
                $customField = $filter->getCustomField();

                if (null === $customField) {
                    continue;
                }

                $item['customFieldName'] = $customField->getName();

                if (
                    !in_array($customField->getType(), self::CUSTOM_FIELD_TYPE_WHITELIST, true)
                ) {
                    continue;
                }

                $customFieldConfig = $customField->getConfig();

                if (
                    isset($customFieldConfig['customFieldType'])
                    && in_array($customFieldConfig['customFieldType'], [ 'entity', 'media' ], true)
                ) {
                    continue;
                }

                /** @var FilterValueEntity[] $filterValues */
                $filterValues = $filter->getValues()->getElements();
                $values       = array_map(
                    fn (FilterValueEntity $value) => [
                        'id'    => $value->getId(),
                        'value' => $value->getValue(),
                        'label' => $this->getLabel(
                            $value->getValue(),
                            $customField,
                            $salesChannelContext
                        ),
                    ],
                    $filterValues
                );

                $item['values'] = array_values($values);
            }

            $result[] = $item;
        }

        return $result;
    }

    private function getLabel(
        string              $value,
        CustomFieldEntity   $customField,
        SalesChannelContext $salesChannelContext
    ): string {
        $config = $customField->getConfig();

        if (!is_array($config)) {
            return $value;
        }

        switch ($customField->getType()) {
            case 'bool':
                return $this->translator->trans(
                    'neti-next-store-locator.index.search.filterLabels.bool.' . $value
                );
            case 'select':
                if (isset($config['options']) && is_array($config['options'])) {
                    $languageCode = $this->getLanguageCode(
                        $salesChannelContext->getLanguageId(),
                        $salesChannelContext->getContext()
                    );

                    /** @var array $option */
                    foreach ($config['options'] as $option) {
                        if (
                            $option['value'] === $value
                            && is_array($option['label'])
                            && isset($option['label'][$languageCode])
                        ) {
                            return (string) $option['label'][$languageCode];
                        }
                    }
                }
                break;
        }

        return $value;
    }

    private function getLanguageCode(string $languageId, Context $context): string
    {
        if (!isset($this->languageCodeCache[$languageId])) {
            $criteria = new Criteria([ $languageId ]);
            $criteria->addAssociation('locale');

            $result = $this->languageRepository->search($criteria, $context);
            /** @var LanguageEntity|null $language */
            $language = $result->first();

            if ($language instanceof LanguageEntity) {
                $locale = $language->getLocale();

                if ($locale instanceof LocaleEntity) {
                    $this->languageCodeCache[$languageId] = $locale->getCode();
                }
            }
        }

        return $this->languageCodeCache[$languageId];
    }
}
