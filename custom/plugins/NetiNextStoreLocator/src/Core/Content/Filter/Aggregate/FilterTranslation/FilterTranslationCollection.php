<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class FilterTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FilterTranslationEntity::class;
    }
}
