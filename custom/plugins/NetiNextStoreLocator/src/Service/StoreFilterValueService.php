<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue\FilterValueEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class StoreFilterValueService
{
    public function __construct(
        private readonly EntityRepository $filterRepository,
        private readonly EntityRepository $filterValueRepository,
        private readonly Connection       $db
    ) {
    }

    public function build(
        string  $filterId,
        Context $context
    ): void {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $filterId));
        $criteria->addAssociation('values');
        $criteria->addAssociation('customField');

        /** @var FilterEntity|null $filter */
        $filter = $this->filterRepository->search($criteria, $context)->first();

        if (null === $filter) {
            return;
        }

        if (
            $filter->getValueType() !== FilterEntity::VALUE_TYPE_CUSTOM_FIELD
            || null === $filter->getCustomField()
        ) {
            return;
        }

        $customField = $filter->getCustomField();

        if (null === $customField) {
            return;
        }

        if (
            !in_array($customField->getType(), StoreFilterService::CUSTOM_FIELD_TYPE_WHITELIST, true)
        ) {
            return;
        }

        $customFieldConfig = $customField->getConfig();

        if (
            isset($customFieldConfig['customFieldType'])
            && in_array($customFieldConfig['customFieldType'], [ 'entity', 'media' ], true)
        ) {
            return;
        }

        $sql = '
            SELECT id, custom_fields
            FROM neti_store_locator
            WHERE active = 1
              AND (longitude IS NOT NULL AND latitude IS NOT NULL)
              AND custom_fields IS NOT NULL
        ';

        /** @var array{id: string, custom_fields: string|null}[] $stores */
        $stores = $this->db->fetchAllAssociative($sql);

        // Fetch all values from all stores
        /** @var string[] $values */
        $values = [];

        foreach ($stores as $store) {
            $customFieldsJson = $store['custom_fields'];

            if (null === $customFieldsJson) {
                continue;
            }

            $customFields = json_decode($customFieldsJson, true);

            if (false === is_array($customFields)) {
                continue;
            }

            /** @var mixed $value */
            $value = $customFields[$customField->getName()] ?? null;

            if ('bool' === $customField->getType()) {
                $value = $value === true ? 'true' : 'false';
            }

            if (is_string($value) || is_int($value) || is_float($value)) {
                $values[] = (string) $value;
            } elseif (is_array($value)) {
                /** @var mixed $nestedValue (required for psalm) */
                foreach ($value as $nestedValue) {
                    if (is_string($nestedValue) || is_int($nestedValue) || is_float($nestedValue)) {
                        $values[] = (string) $nestedValue;
                    }
                }
            }
        }

        $values = array_values(array_unique($values));

        // Sync values with filter values
        $removedValues = [];

        $addedValues = [];

        foreach ($values as $value) {
            $entity = $filter->getValues()->getByValue($value);

            if (null === $entity) {
                $addedValues[] = [
                    'id'       => md5($value),
                    'filterId' => $filterId,
                    'value'    => $value,
                ];
            }
        }

        /** @var FilterValueEntity $value */
        foreach ($filter->getValues()->getElements() as $value) {
            if (false === in_array($value->getValue(), $values, true)) {
                $removedValues[] = [
                    'id' => $value->getId(),
                ];
            }
        }

        if ($addedValues !== []) {
            $this->filterValueRepository->create($addedValues, $context);
        }

        if ($removedValues !== []) {
            $this->filterValueRepository->delete($removedValues, $context);
        }
    }
}
