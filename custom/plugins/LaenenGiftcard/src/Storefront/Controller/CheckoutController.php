<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Storefront\Controller;

use Laenen\Giftcard\Core\Checkout\GiftcardCartService;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class CheckoutController extends StorefrontController
{
    public function __construct(
        private GiftcardCartService $giftcardCartService
    ) {
    }

    #[Route(path: '/checkout/giftcard/remove', name: 'frontend.checkout.giftcard.remove', methods: ["POST"])]
    public function giftcardRemove(RequestDataBag $data, Cart $cart, SalesChannelContext $context): Response
    {
        $this->giftcardCartService->remove(
            $cart,
            $data->get('code'),
            $context
        );

        return $this->redirectToRoute('frontend.checkout.cart.page');
    }
}
