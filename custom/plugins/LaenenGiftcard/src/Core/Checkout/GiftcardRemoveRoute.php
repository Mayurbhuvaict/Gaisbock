<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Laenen\Giftcard\Core\Checkout\Event\AfterGiftcardRemovedEvent;
use Laenen\Giftcard\Core\Checkout\Event\BeforeGiftcardRemovedEvent;
use Laenen\Giftcard\Core\Checkout\Exception\GiftcardNotFoundFoundException;
use Laenen\Giftcard\Core\Checkout\Exception\NoGiftcardsFoundException;
use Laenen\Giftcard\Struct\AppliedGiftcardStructCollection;
use Shopware\Core\Checkout\Cart\AbstractCartPersister;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartCalculator;
use Shopware\Core\Checkout\Cart\Event\CartChangedEvent;
use Shopware\Core\Checkout\Cart\SalesChannel\CartResponse;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(defaults: ['_routeScope' => ['store-api']])]
class GiftcardRemoveRoute extends AbstractGiftcardRemoveRoute
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private CartCalculator $cartCalculator,
        private AbstractCartPersister $cartPersister
    ) {
    }

    public function getDecorated(): AbstractGiftcardRemoveRoute
    {
        throw new DecorationPatternException(self::class);
    }

    #[Route(path: '/store-api/checkout/cart/giftcard', name: 'store-api.checkout.cart.remove-giftcard', methods: ['DELETE'])]
    public function remove(Request $request, Cart $cart, SalesChannelContext $context): CartResponse
    {
        $codes = $request->get('codes');
        $giftcards = [];

        $appliedGiftcards = $cart->getExtension(RedeemGiftcardCollector::GIFTCARDS);
        if (!$appliedGiftcards instanceof AppliedGiftcardStructCollection) {
            throw new NoGiftcardsFoundException();
        }

        foreach ($codes as $code) {
            $giftcard = $appliedGiftcards->get($code);
            $giftcards[] = $giftcard;

            if (!$giftcard) {
                throw new GiftcardNotFoundFoundException($code);
            }

            $appliedGiftcards->remove($code);

            $this->eventDispatcher->dispatch(new BeforeGiftcardRemovedEvent($giftcard, $cart, $context));

            $cart->markModified();
        }

        $cart->addExtension(RedeemGiftcardCollector::GIFTCARDS, $appliedGiftcards);

        $cart = $this->cartCalculator->calculate($cart, $context);
        $this->cartPersister->save($cart, $context);

        $this->eventDispatcher->dispatch(new AfterGiftcardRemovedEvent($giftcards, $cart, $context));

        $this->eventDispatcher->dispatch(new CartChangedEvent($cart, $context));

        return new CartResponse($cart);
    }
}
