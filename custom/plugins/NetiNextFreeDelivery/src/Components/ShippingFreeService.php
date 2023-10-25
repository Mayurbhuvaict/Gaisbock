<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Components;

use NetInventors\NetiNextFreeDelivery\Struct\FreeDeliveryResult;
use NetInventors\NetiNextFreeDelivery\Struct\PluginConfigStruct;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * @psalm-suppress DeprecatedClass - CartService is markup as deprecated for SW 6.6
 */
class ShippingFreeService
{
    public function __construct(
        private readonly PluginConfigStruct $pluginConfig,
        private readonly CartService        $cartService,
        private readonly DeliveryCalculator $deliveryCalculator,
        private readonly string             $shopwareVersion
    ) {
    }

    /**
     * Function to calculate the ShippingCostsDifference
     */
    public function calculateShippingCostsDifference(SalesChannelContext $context): ?FreeDeliveryResult
    {
        $cart = $this->cartService->getCart($context->getToken(), $context);

        $totalPrice        = $this->getCartTotalPrice($cart);
        $shippingFreeLimit = $this->deliveryCalculator->calculateLimit($context, $cart);

        if ($shippingFreeLimit <= 0) {
            return null;
        }

        if ([] !== $cart->getLineItems()->getElements() && 0.0 === $cart->getShippingCosts()->getTotalPrice()) {
            return new FreeDeliveryResult(0.0, $shippingFreeLimit);
        }

        $shippingCostDifference = $shippingFreeLimit - $totalPrice;
        $displayFrom            = $this->pluginConfig->getDisplayFrom();

        if ($displayFrom > 0 && $displayFrom < $shippingCostDifference) {
            return null;
        }

        return new FreeDeliveryResult(
            $shippingCostDifference,
            $shippingFreeLimit
        );
    }

    private function getCartTotalPrice(Cart $cart): float
    {
        $totalPrice = $cart->getPrice()->getPositionPrice();

        foreach ($cart->getLineItems() as $lineItem) {
            $price = $lineItem->getPrice();

            if ($price && $this->ignoreShippingFree($lineItem)) {
                $totalPrice -= $price->getTotalPrice();
            }
        }

        return $totalPrice;
    }

    private function ignoreShippingFree(LineItem $lineItem): bool
    {
        $deliveryInformation = $lineItem->getDeliveryInformation();

        if (
            null === $deliveryInformation
            || version_compare($this->shopwareVersion, '6.4.1.0', '<')
        ) {
            return false;
        }

        return $deliveryInformation->getFreeDelivery();
    }
}
