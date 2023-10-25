<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Subscriber;

use NetInventors\NetiNextFreeDelivery\Components\ShippingFreeService;
use NetInventors\NetiNextFreeDelivery\Struct\PluginConfigStruct;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Frontend implements EventSubscriberInterface
{
    public function __construct(
        private readonly PluginConfigStruct  $pluginConfig,
        private readonly ShippingFreeService $shippingFreeService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class       => 'onProductLoaded',
            CheckoutCartPageLoadedEvent::class  => 'onCheckoutPageLoaded',
            OffcanvasCartPageLoadedEvent::class => 'onAjaxCartPageLoaded',
            HeaderPageletLoadedEvent::class     => 'headerLoadedEvent',
        ];
    }

    public function headerLoadedEvent(HeaderPageletLoadedEvent $event): void
    {
        $isActivated = $this->pluginConfig->isSubshopActivated();
        $show        = $this->pluginConfig->isShowInHeader();

        if (!$isActivated || !$show) {
            return;
        }

        $difference = $this->shippingFreeService->calculateShippingCostsDifference(
            $event->getSalesChannelContext()
        );

        if ($difference) {
            $event->getPagelet()->addExtension(
                'netiNextFreeDelivery',
                $difference
            );
        }
    }

    public function onAjaxCartPageLoaded(OffcanvasCartPageLoadedEvent $event): void
    {
        $isActivated = $this->pluginConfig->isSubshopActivated();
        $show        = $this->pluginConfig->isShowInModal();

        if (!$isActivated || !$show) {
            return;
        }

        $event->getPage()->assign(
            [
                'netiNextFreeDelivery' => $this->shippingFreeService->calculateShippingCostsDifference(
                    $event->getSalesChannelContext()
                ),
            ]
        );
    }

    public function onCheckoutPageLoaded(CheckoutCartPageLoadedEvent $event): void
    {
        $isActivated = $this->pluginConfig->isSubshopActivated();

        if (!$isActivated) {
            return;
        }

        $event->getPage()->assign(
            [
                'netiNextFreeDelivery' => $this->shippingFreeService->calculateShippingCostsDifference(
                    $event->getSalesChannelContext()
                ),
            ]
        );
    }

    public function onProductLoaded(ProductPageLoadedEvent $event): void
    {
        $isActivated = $this->pluginConfig->isSubshopActivated();
        $show        = $this->pluginConfig->isShowInArticle();

        if (!$isActivated || !$show) {
            return;
        }

        $difference = $this->shippingFreeService->calculateShippingCostsDifference(
            $event->getSalesChannelContext()
        );

        $event->getPage()->assign(
            [
                'netiNextFreeDelivery' => $difference,
            ]
        );
    }
}
