<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class StoreTranslationEntity extends TranslationEntity
{
    protected string  $netiStoreLocatorId    = '';

    protected ?string $description           = null;

    protected ?string $additionalInformation = null;

    protected ?string $seoTitle              = null;

    protected ?string $seoUrl                = null;

    protected ?string $seoDescription        = null;

    protected ?string $detailTitle           = null;

    protected ?string $openingTimes          = null;

    public function getNetiStoreLocatorId(): string
    {
        return $this->netiStoreLocatorId;
    }

    public function setNetiStoreLocatorId(string $netiStoreLocatorId): void
    {
        $this->netiStoreLocatorId = $netiStoreLocatorId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): void
    {
        $this->additionalInformation = $additionalInformation;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    public function getSeoUrl(): ?string
    {
        return $this->seoUrl;
    }

    public function setSeoUrl(string $seoUrl): void
    {
        $this->seoUrl = $seoUrl;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    public function getDetailTitle(): ?string
    {
        return $this->detailTitle;
    }

    public function setDetailTitle(?string $detailTitle): void
    {
        $this->detailTitle = $detailTitle;
    }

    public function getOpeningTimes(): ?string
    {
        return $this->openingTimes;
    }

    public function setOpeningTimes(?string $openingTimes): void
    {
        $this->openingTimes = $openingTimes;
    }
}
