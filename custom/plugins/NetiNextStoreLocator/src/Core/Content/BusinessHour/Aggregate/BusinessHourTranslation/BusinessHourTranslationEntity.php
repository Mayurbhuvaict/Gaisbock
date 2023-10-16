<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\Aggregate\BusinessHourTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class BusinessHourTranslationEntity extends TranslationEntity
{
    protected string  $businessHourId = '';

    protected ?string $description    = null;

    public function getBusinessHourId(): string
    {
        return $this->businessHourId;
    }

    public function setBusinessHourId(string $businessHourId): void
    {
        $this->businessHourId = $businessHourId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
