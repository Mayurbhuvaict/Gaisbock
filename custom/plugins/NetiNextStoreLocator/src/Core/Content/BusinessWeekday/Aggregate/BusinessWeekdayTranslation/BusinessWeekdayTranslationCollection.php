<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\Aggregate\BusinessWeekdayTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class BusinessWeekdayTranslationCollection extends EntityCollection
{
    public function filterByLanguageId(string $id): self
    {
        return $this->filter(
            fn (BusinessWeekdayTranslationEntity $entity) => $entity->getLanguageId() === $id
        );
    }

    protected function getExpectedClass(): string
    {
        return BusinessWeekdayTranslationEntity::class;
    }
}
