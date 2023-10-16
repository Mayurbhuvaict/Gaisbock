<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class FilterTranslationEntity extends TranslationEntity
{
    protected string        $netiSlFilterId = '';

    protected ?FilterEntity $netiSlFilter   = null;

    protected ?string       $title          = null;

    public function getNetiSlFilterId(): string
    {
        return $this->netiSlFilterId;
    }

    public function setNetiSlFilterId(string $netiSlFilterId): void
    {
        $this->netiSlFilterId = $netiSlFilterId;
    }

    public function getNetiSlFilter(): ?FilterEntity
    {
        return $this->netiSlFilter;
    }

    public function setNetiSlFilter(?FilterEntity $netiSlFilter): void
    {
        $this->netiSlFilter = $netiSlFilter;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
