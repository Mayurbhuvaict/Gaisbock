<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Laenen\Giftcard\Service\GiftcardGateway;
use Laenen\Giftcard\Struct\AppliedGiftcardStruct;
use Laenen\Giftcard\Struct\AppliedGiftcardStructCollection;
use Laenen\Giftcard\Struct\GiftcardAmount;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Laenen\Giftcard\Struct\GiftcardStructCollection;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Checkout\Promotion\Cart\Extension\CartExtension;
use Shopware\Core\Checkout\Promotion\Cart\PromotionProcessor;
use Shopware\Core\Content\Product\Cart\ProductGatewayInterface;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class BuyGiftcardCollector implements CartDataCollectorInterface, CartProcessorInterface
{
    public function __construct(
        private ProductGatewayInterface $productGateway
    ) {
    }

    public function collect(
        CartDataCollection $data,
        Cart $original,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $lineItems = $original->getLineItems()->filterType(RedeemGiftcardCollector::GIFTCARDS);
        $productIds = array_values(array_filter(array_unique($lineItems->getReferenceIds())));

        if (empty($productIds)) {
            return;
        }

        $data->set(self::class, $this->productGateway->get($productIds, $context));
    }

    public function process(
        CartDataCollection $data,
        Cart $original,
        Cart $toCalculate,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $products = $data->get(self::class);
        if (!$products instanceof ProductCollection) {
            return;
        }

        /** @var LineItem[] $lineItems */
        $lineItems = $original->getLineItems()->filterType(RedeemGiftcardCollector::GIFTCARDS);
        foreach ($lineItems as $lineItem) {
            $product = $products->get($lineItem->getReferencedId());
            $giftcardAmount = $lineItem->getExtension('giftcardAmount');
            if (!$product instanceof ProductEntity || !$giftcardAmount instanceof GiftcardAmount) {
                continue;
            }
            $productCustomFields = $product->getCustomFields() ?? [];

            $amount = $giftcardAmount->getAmount();

            $min = $productCustomFields['laeGiftcardMinValue'] ?? 0;
            $max = $productCustomFields['laeGiftcardMaxValue'] ?? 500;

            if ($amount < $min) {
                $amount = $min;
            }
            if ($amount > $max) {
                $amount = $max;
            }

            $lineItem->setLabel($product->getTranslation('name'));

            if ($product->getCover()) {
                $lineItem->setCover($product->getCover()->getMedia());
            }

            $lineItem->setDeliveryInformation(new DeliveryInformation(
                1000, // Digital product, so never out of stock
                0,
                true,
            ));
            $lineItem->setRemovable(true);
            $lineItem->setStackable(false);
            $lineItem->setPrice(new CalculatedPrice(
                $amount,
                $amount,
                new CalculatedTaxCollection(),
                new TaxRuleCollection()
            ));

            $toCalculate->add($lineItem);
        }
    }
}
