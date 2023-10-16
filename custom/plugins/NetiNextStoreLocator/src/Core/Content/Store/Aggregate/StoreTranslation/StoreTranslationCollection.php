<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class StoreTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return StoreTranslationEntity::class;
    }
}
