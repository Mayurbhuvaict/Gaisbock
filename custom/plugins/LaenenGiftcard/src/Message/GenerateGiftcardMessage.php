<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Message;

use Shopware\Core\System\SalesChannel\SalesChannelContext;

class GenerateGiftcardMessage
{
    public function __construct(
        private string $orderId,
        private string $giftcardId,
        private SalesChannelContext $salesChannelContext
    ) {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getGiftcardId(): string
    {
        return $this->giftcardId;
    }

    public function setGiftcardId(string $giftcardId): void
    {
        $this->giftcardId = $giftcardId;
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }

    public function setSalesChannelContext(SalesChannelContext $salesChannelContext): void
    {
        $this->salesChannelContext = $salesChannelContext;
    }
}
