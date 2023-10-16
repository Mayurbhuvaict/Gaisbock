<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class FilterValueEntity extends Entity
{
    use EntityIdTrait;

    protected string        $filterId = '';

    protected ?FilterEntity $filter   = null;

    protected string        $value    = '';

    /**
     * @psalm-mutation-free
     */
    public function getFilterId(): string
    {
        return $this->filterId;
    }

    public function setFilterId(string $filterId): void
    {
        $this->filterId = $filterId;
    }

    /**
     * @psalm-mutation-free
     */
    public function getFilter(): ?FilterEntity
    {
        return $this->filter;
    }

    public function setFilter(?FilterEntity $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @psalm-mutation-free
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
