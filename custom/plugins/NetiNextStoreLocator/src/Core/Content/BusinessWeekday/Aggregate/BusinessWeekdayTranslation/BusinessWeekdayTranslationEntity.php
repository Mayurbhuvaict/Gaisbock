<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\Aggregate\BusinessWeekdayTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class BusinessWeekdayTranslationEntity extends TranslationEntity
{
    protected string $businessWeekdayId = '';

    protected string $name              = '';

    public function getBusinessWeekdayId(): string
    {
        return $this->businessWeekdayId;
    }

    public function setBusinessWeekdayId(string $businessWeekdayId): void
    {
        $this->businessWeekdayId = $businessWeekdayId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
