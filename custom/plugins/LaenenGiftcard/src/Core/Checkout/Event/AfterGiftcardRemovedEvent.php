<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Event;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\ShopwareSalesChannelEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class AfterGiftcardRemovedEvent implements ShopwareSalesChannelEvent
{
    public function __construct(
        protected array $giftcards,
        protected Cart $cart,
        protected SalesChannelContext $salesChannelContext
    ) {
    }

    public function getGiftcards(): array
    {
        return $this->giftcards;
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
