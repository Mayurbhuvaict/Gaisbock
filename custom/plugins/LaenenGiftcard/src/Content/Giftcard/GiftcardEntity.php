<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class GiftcardEntity extends Entity
{
    use EntityIdTrait;
    use EntityCustomFieldsTrait;

    protected ?string $name;

    protected ?string $description;

    protected ?float $balance;

    protected ?float $initialAmount;

    protected string $code;

    protected string $type;

    protected string $originOrderId;

    protected string $originProductId;

    protected string $salesChannelId;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): void
    {
        $this->balance = $balance;
    }

    public function getInitialAmount(): ?float
    {
        return $this->initialAmount;
    }

    public function setInitialAmount(?float $initialAmount): void
    {
        $this->initialAmount = $initialAmount;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getOriginOrderId(): string
    {
        return $this->originOrderId;
    }

    public function setOriginOrderId(string $originOrderId): void
    {
        $this->originOrderId = $originOrderId;
    }

    public function getOriginProductId(): string
    {
        return $this->originProductId;
    }

    public function setOriginProductId(string $originProductId): void
    {
        $this->originProductId = $originProductId;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }
}
