<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreCms;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class StoreCmsEntity extends Entity
{
    use EntityIdTrait;

    protected string         $storeId   = '';

    protected ?StoreEntity   $store     = null;

    protected string         $cmsPageId = '';

    protected ?CmsPageEntity $cmsPage   = null;

    protected int            $position  = 0;

    /**
     * @psalm-mutation-free
     */
    public function getStoreId(): string
    {
        return $this->storeId;
    }

    public function setStoreId(string $storeId): void
    {
        $this->storeId = $storeId;
    }

    /**
     * @psalm-mutation-free
     */
    public function getStore(): ?StoreEntity
    {
        return $this->store;
    }

    public function setStore(?StoreEntity $store): void
    {
        $this->store = $store;
    }

    /**
     * @psalm-mutation-free
     */
    public function getCmsPageId(): string
    {
        return $this->cmsPageId;
    }

    public function setCmsPageId(string $cmsPageId): void
    {
        $this->cmsPageId = $cmsPageId;
    }

    /**
     * @psalm-mutation-free
     */
    public function getCmsPage(): ?CmsPageEntity
    {
        return $this->cmsPage;
    }

    public function setCmsPage(?CmsPageEntity $cmsPage): void
    {
        $this->cmsPage = $cmsPage;
    }

    /**
     * @psalm-mutation-free
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
