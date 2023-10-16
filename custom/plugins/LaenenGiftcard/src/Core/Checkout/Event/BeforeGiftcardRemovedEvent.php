<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Event;

use Laenen\Giftcard\Struct\AppliedGiftcardStruct;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\ShopwareSalesChannelEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class BeforeGiftcardRemovedEvent implements ShopwareSalesChannelEvent
{
    public function __construct(
        protected AppliedGiftcardStruct $giftcard,
        protected Cart $cart,
        protected SalesChannelContext $salesChannelContext
    ) {
    }

    public function getGiftcard(): AppliedGiftcardStruct
    {
        return $this->giftcard;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getContext(): Context
    {
        return $this->salesChannelContext->getContext();
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }
}
