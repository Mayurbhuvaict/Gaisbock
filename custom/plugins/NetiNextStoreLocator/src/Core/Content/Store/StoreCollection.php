<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class StoreCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StoreEntity::class;
    }
}
