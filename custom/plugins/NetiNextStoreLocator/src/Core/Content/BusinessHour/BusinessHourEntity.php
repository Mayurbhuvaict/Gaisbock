<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class BusinessHourEntity extends Entity
{
    use EntityIdTrait;

    protected string  $start       = '';

    protected string  $end         = '';

    protected ?string $description = null;

    public function getStart(): string
    {
        return $this->start;
    }

    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    public function setEnd(string $end): void
    {
        $this->end = $end;
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
