<?php declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\SalesChannel\CartResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * This route can be used to remove applied giftcards from cart
 */
abstract class AbstractGiftcardRemoveRoute
{
    abstract public function getDecorated(): AbstractGiftcardRemoveRoute;

    abstract public function remove(Request $request, Cart $cart, SalesChannelContext $context): CartResponse;
}
