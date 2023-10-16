<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreBusinessHour;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\BusinessHourEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\BusinessWeekdayEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class StoreBusinessHourEntity extends Entity
{
    use EntityIdTrait;

    protected string                 $storeId           = '';

    protected ?StoreEntity           $store             = null;

    protected string                 $businessHourId    = '';

    protected ?BusinessHourEntity    $businessHour      = null;

    protected ?string                $businessWeekdayId = '';

    protected ?BusinessWeekdayEntity $businessWeekday   = null;

    protected ?string                $specialDate       = null;

    protected bool                   $annual            = false;

    protected bool                   $active            = false;

    public function getStoreId(): string
    {
        return $this->storeId;
    }

    public function setStoreId(string $storeId): void
    {
        $this->storeId = $storeId;
    }

    public function getStore(): ?StoreEntity
    {
        return $this->store;
    }

    public function setStore(?StoreEntity $store): void
    {
        $this->store = $store;
    }

    public function getBusinessHourId(): string
    {
        return $this->businessHourId;
    }

    public function setBusinessHourId(string $businessHourId): void
    {
        $this->businessHourId = $businessHourId;
    }

    public function getBusinessHour(): ?BusinessHourEntity
    {
        return $this->businessHour;
    }

    public function setBusinessHour(?BusinessHourEntity $businessHour): void
    {
        $this->businessHour = $businessHour;
    }

    public function getBusinessWeekdayId(): ?string
    {
        return $this->businessWeekdayId;
    }

    public function setBusinessWeekdayId(?string $businessWeekdayId): void
    {
        $this->businessWeekdayId = $businessWeekdayId;
    }

    public function getBusinessWeekday(): ?BusinessWeekdayEntity
    {
        return $this->businessWeekday;
    }

    public function setBusinessWeekday(?BusinessWeekdayEntity $businessWeekday): void
    {
        $this->businessWeekday = $businessWeekday;
    }

    public function getSpecialDate(): ?string
    {
        return $this->specialDate;
    }

    public function setSpecialDate(?string $specialDate): void
    {
        $this->specialDate = $specialDate;
    }

    public function isAnnual(): bool
    {
        return $this->annual;
    }

    public function setAnnual(bool $annual): void
    {
        $this->annual = $annual;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
