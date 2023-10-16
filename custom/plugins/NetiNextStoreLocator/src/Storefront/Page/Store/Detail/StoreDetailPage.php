<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Storefront\Page\Page;

class StoreDetailPage extends Page
{
    protected ?StoreEntity $store                 = null;

    protected array        $config                = [];

    protected array        $contactFormFields     = [];

    protected array        $contactSubjectOptions = [];

    protected ?string      $htmlContent           = null;

    private array          $storeBusinessHours    = [];

    private array          $weekDays              = [];

    private bool           $isStoreOpen           = false;

    public function setStore(?StoreEntity $store): void
    {
        $this->store = $store;
    }

    public function getStore(): ?StoreEntity
    {
        return $this->store;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getContactFormFields(): array
    {
        return $this->contactFormFields;
    }

    public function setContactFormFields(array $contactFormFields): void
    {
        $this->contactFormFields = $contactFormFields;
    }

    public function getContactSubjectOptions(): array
    {
        return $this->contactSubjectOptions;
    }

    public function setContactSubjectOptions(array $contactSubjectOptions): void
    {
        $this->contactSubjectOptions = $contactSubjectOptions;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    public function setHtmlContent(?string $htmlContent): void
    {
        $this->htmlContent = $htmlContent;
    }

    public function getStoreBusinessHours(): array
    {
        return $this->storeBusinessHours;
    }

    public function setStoreBusinessHours(array $storeBusinessHours): void
    {
        $this->storeBusinessHours = $storeBusinessHours;
    }

    public function getWeekDays(): array
    {
        return $this->weekDays;
    }

    public function setWeekDays(array $weekDays): void
    {
        $this->weekDays = $weekDays;
    }

    public function isStoreOpen(): bool
    {
        return $this->isStoreOpen;
    }

    public function setIsStoreOpen(bool $isStoreOpen): void
    {
        $this->isStoreOpen = $isStoreOpen;
    }
}
