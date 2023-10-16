<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class BusinessWeekdayCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return BusinessWeekdayEntity::class;
    }
}
