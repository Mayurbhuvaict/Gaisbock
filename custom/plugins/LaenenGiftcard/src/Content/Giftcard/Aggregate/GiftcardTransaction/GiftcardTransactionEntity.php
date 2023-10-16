<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard\Aggregate\GiftcardTransaction;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class GiftcardTransactionEntity extends Entity
{
    use EntityIdTrait;
    use EntityCustomFieldsTrait;

    protected string $giftcardId;

    protected ?GiftcardEntity $giftcard = null;

    protected float $amount;

    protected ?string $orderId;

    protected ?OrderEntity $order = null;

    protected string $comment;

    public function getGiftcardId(): string
    {
        return $this->giftcardId;
    }

    public function setGiftcardId(string $giftcardId): void
    {
        $this->giftcardId = $giftcardId;
    }

    public function getGiftcard(): ?GiftcardEntity
    {
        return $this->giftcard;
    }

    public function setGiftcard(?GiftcardEntity $giftcard): void
    {
        $this->giftcard = $giftcard;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrder(): ?OrderEntity
    {
        return $this->order;
    }

    public function setOrder(?OrderEntity $order): void
    {
        $this->order = $order;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
