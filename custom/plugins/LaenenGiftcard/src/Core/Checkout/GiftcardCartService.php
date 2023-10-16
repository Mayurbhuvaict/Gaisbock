<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class GiftcardCartService
{
    public function __construct(
        private AbstractGiftcardRemoveRoute $giftcardRemoveRoute
    ) {
    }

    public function remove(Cart $cart, string $code, SalesChannelContext $context): Cart
    {
        $request = new Request();
        $request->request->set('codes', [$code]);

        return $this->giftcardRemoveRoute->remove($request, $cart, $context)->getCart();
    }
}
