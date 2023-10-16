<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class FilterValueCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FilterValueEntity::class;
    }

    public function getByValue(string $value): ?FilterValueEntity
    {
        /** @var FilterValueCollection $filters */
        $filters = $this->filterByProperty('value', $value);

        /** @var FilterValueEntity|null $filter */
        $filter = $filters->first();

        return $filter;
    }
}
