<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\Aggregate\BusinessHourTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class BusinessHourTranslationCollection extends EntityCollection
{
    public function filterByLanguageId(string $id): self
    {
        return $this->filter(
            fn (BusinessHourTranslationEntity $entity) => $entity->getLanguageId() === $id
        );
    }

    protected function getExpectedClass(): string
    {
        return BusinessHourTranslationEntity::class;
    }
}
