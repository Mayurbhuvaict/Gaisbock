<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class BusinessHourCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return BusinessHourEntity::class;
    }
}
