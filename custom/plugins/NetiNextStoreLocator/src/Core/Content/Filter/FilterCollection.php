<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class FilterCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FilterEntity::class;
    }
}
