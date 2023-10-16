<?php declare(strict_types=1);

namespace ProxaBasketCrossSellingSW6\Subscriber\Storefront;

use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ProxaBasketCrossSellingSW6\Service\GetSliderProductsService;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;

class EventSubscriber implements EventSubscriberInterface
{
    /**
     * @var GetSliderProductsService
     */
    private $getSliderProductsService;

    /**
     * EventSubscriber constructor.
     * @param GetSliderProductsService $getSliderProductsService
     */
    public function __construct(GetSliderProductsService $getSliderProductsService)
    {
        $this->getSliderProductsService = $getSliderProductsService;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutCartPageLoadedEvent::class => 'checkoutCart',
            OffcanvasCartPageLoadedEvent::class => 'checkoutCart'
        ];
    }

    /**
     * @param PageLoadedEvent $event
     */
    public function checkoutCart(PageLoadedEvent $event)
    {
        if ($event instanceof CheckoutCartPageLoadedEvent || $event instanceof OffcanvasCartPageLoadedEvent) {
            $cart = $event->getPage()->getCart();
            $cartItems = $cart->getLineItems();
            $salesChannelContext = $event->getSalesChannelContext();

            if ($event instanceof OffcanvasCartPageLoadedEvent) {
                $viewType = 'ajax_cart';
            } else {
                $viewType = 'cart';
            }

            $cartItemNumbers = [];
            foreach ($cartItems as $cartItem) {
                if ($cartItem->getType() === LineItem::PRODUCT_LINE_ITEM_TYPE) {
                    $cartItemNumbers[] = $cartItem->getPayload()['productNumber'];
                }
            }

            $cartItemNumbers = array_reverse($cartItemNumbers);

            $this->getSliderProductsService->setViewType($viewType);
            $this->getSliderProductsService->setContext($salesChannelContext);

            $this->getSliderProductsService->setCartItemNumbers($cartItemNumbers);
            $sliderProducts = $this->getSliderProductsService->getSliderProducts();
            $sliderType = $this->getSliderProductsService->getSliderTypeInAjaxCart();

            $cart->addExtension('sliderProducts', new ArrayEntity($sliderProducts));
            $cart->addExtension('sliderType', new ArrayEntity([$sliderType]));
        }
    }
}