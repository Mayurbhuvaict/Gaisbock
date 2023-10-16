<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreCms;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class StoreCmsCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StoreCmsEntity::class;
    }
}
