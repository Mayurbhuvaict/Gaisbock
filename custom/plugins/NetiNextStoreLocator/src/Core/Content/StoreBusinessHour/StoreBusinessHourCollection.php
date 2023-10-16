<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreBusinessHour;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class StoreBusinessHourCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StoreBusinessHourEntity::class;
    }
}
