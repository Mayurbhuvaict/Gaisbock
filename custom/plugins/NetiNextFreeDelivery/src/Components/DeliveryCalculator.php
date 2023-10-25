<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Components;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Delivery\Struct\Delivery;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * @psalm-type ShippingMethodPrice = array{
 *    id: string,
 *    shipping_method_id: string,
 *    calculation: int|null,
 *    rule_id: string|null,
 *    calculation_rule_id: string|null,
 *    currency_price: string|null,
 *    quantity_start: float|null,
 *    quantity_end: float|null,
 *    custom_fields: array|null,
 *    price: float|null
 * }
 *
 * @psalm-type CurrencyPrice = array{
 *    net: float,
 *    gross: float,
 *    linked: boolean,
 *    currencyId: string
 * }
 */
class DeliveryCalculator
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    /**
     * Calculates the smallest limit to get free shipping.
     *
     * This method uses partly logic from the original DeliveryCalculator class.
     *
     * We do like shopware:
     * - Sorting prices ASC
     * - Filtering non-matching rules
     * - Checking if the matrix match because the basket is empty.
     *
     * @param SalesChannelContext $context
     * @param Cart                $cart
     *
     * @return float
     * @throws Exception
     */
    public function calculateLimit(SalesChannelContext $context, Cart $cart): float
    {
        $totalAmount = 0.0;

        $prices        = $this->getPrices($context);
        $prices        = $this->filterPricesByRule($context, $prices);
        $matchedPrices = $this->filterPricesByMatrix($context, $cart, $prices, $totalAmount);

        return $this->findLimit($prices, $matchedPrices, $totalAmount);
    }

    /**
     * @param SalesChannelContext $context
     *
     * @return array
     * @throws \Doctrine\DBAL\Exception
     *
     * @psalm-return ShippingMethodPrice[]
     */
    private function getPrices(SalesChannelContext $context): array
    {
        $sql = '
            SELECT *
            FROM shipping_method_price
            WHERE HEX(shipping_method_id) = :id
              AND calculation = :calculationId
            ORDER BY quantity_start ASC
        ';

        /**
         * @var array $prices
         * @psalm-var ShippingMethodPrice[] $prices
         */
        $prices = $this->connection->executeQuery(
            $sql,
            [
                'id'            => $context->getShippingMethod()->getId(),
                'calculationId' => \Shopware\Core\Checkout\Cart\Delivery\DeliveryCalculator::CALCULATION_BY_PRICE,
            ]
        )->fetchAllAssociative();

        foreach ($prices as &$price) {
            $price = $this->fixPriceField($price, $context);
        }

        unset($price);

        return $prices;
    }

    /**
     * We need to read the price dynamically from the currency_price field.
     *
     * @param array               $price
     * @param SalesChannelContext $context
     *
     * @return array
     *
     * @psalm-param ShippingMethodPrice $price
     */
    private function fixPriceField(array $price, SalesChannelContext $context): array
    {
        if (false === is_string($price['currency_price'])) {
            return $price;
        }

        /**
         * @var array|false $parsedCurrencyPrice
         * @psalm-var CurrencyPrice[]|false $parsedCurrencyPrice
         */
        $parsedCurrencyPrice = json_decode($price['currency_price'], true);

        if (false === is_array($parsedCurrencyPrice)) {
            return $price;
        }

        $currencyPrice = $this->getPriceByCurrency(
            $parsedCurrencyPrice,
            $context->getCurrency()
        );

        switch ($context->getTaxState()) {
            case CartPrice::TAX_STATE_NET:
            case CartPrice::TAX_STATE_FREE:
                $price['price'] = $currencyPrice['net'];
                break;
            case CartPrice::TAX_STATE_GROSS:
                $price['price'] = $currencyPrice['gross'];
                break;
        }

        return $price;
    }

    /**
     * Get price by matching currencyId
     *
     * @param array          $prices
     * @param CurrencyEntity $currency
     *
     * @return array
     *
     * @psalm-param  CurrencyPrice[]  $prices
     *
     * @psalm-return CurrencyPrice
     */
    private function getPriceByCurrency(array $prices, CurrencyEntity $currency): array
    {
        $hasDefaultCurrency = false;
        $defaultCurrency    = [
            'net'        => 0,
            'gross'      => 0,
            'linked'     => false,
            'currencyId' => $currency->getId(),
        ];

        foreach ($prices as $price) {
            if ($price['currencyId'] === $currency->getId()) {
                return $price;
            }

            if (!$hasDefaultCurrency && $price['currencyId'] === Defaults::CURRENCY) {
                $currencyFactor  = $currency->getFactor();
                $defaultCurrency = [
                    'net'        => $price['net'] * $currencyFactor,
                    'gross'      => $price['gross'] * $currencyFactor,
                    'linked'     => false,
                    'currencyId' => $currency->getId(),
                ];

                $hasDefaultCurrency = true;
            }
        }

        return $defaultCurrency;
    }

    /**
     * This filters prices by matching ruleIds. If no matches are found, it returns prices without ruleId.
     *
     * @param SalesChannelContext $context
     * @param array               $prices
     *
     * @return array
     *
     * @psalm-param  ShippingMethodPrice[] $prices
     *
     * @psalm-return ShippingMethodPrice[]
     */
    private function filterPricesByRule(SalesChannelContext $context, array $prices): array
    {
        $result = array_filter(
            $prices,
            /**
             * @psalm-param ShippingMethodPrice $row
             */
            static function ($row) use ($context) {
                if ($row['rule_id'] === null) {
                    return false;
                }

                return in_array(
                    Uuid::fromBytesToHex($row['rule_id']),
                    $context->getRuleIds(),
                    true
                );
            }
        );

        if ([] === $result) {
            $result = array_filter(
                $prices,
                static fn ($row) => $row['rule_id'] === null
            );
        }

        return $result;
    }

    /**
     * This filters prices by matching matrix. This is the equivalent of:
     *
     * src/Core/Checkout/Cart/Delivery/DeliveryCalculator.php::calculateDelivery (line 102-104)
     * if (!$this->matches($delivery, $price, $context)) {
     *     continue;
     * }
     *
     * @param SalesChannelContext $context
     * @param Cart                $cart
     * @param array               $prices
     * @param float               $value
     *
     * @return array
     *
     * @psalm-param  ShippingMethodPrice[] $prices
     *
     * @psalm-return ShippingMethodPrice[]
     */
    private function filterPricesByMatrix(
        SalesChannelContext $context,
        Cart                $cart,
        array               $prices,
        float               &$value = 0
    ): array {
        $delivery = null;

        foreach ($cart->getDeliveries() as $delivery) {
            if ($delivery->getShippingMethod()->getId() === $context->getShippingMethod()->getId()) {
                break;
            }
        }

        if (!($delivery instanceof Delivery)) {
            return $prices;
        }

        $value = $delivery->getPositions()->getPrices()->sum()->getTotalPrice();

        return array_filter(
            $prices,
            static function ($row) use ($context, $value) {
                if ($value <= 0) {
                    return true;
                }

                if ($row['calculation_rule_id']) {
                    return in_array(Uuid::fromBytesToHex($row['calculation_rule_id']), $context->getRuleIds(), true);
                }

                $start = $row['quantity_start'];
                $end   = $row['quantity_end'];

                return ($value >= $start) && (!$end || $value <= $end);
            }
        );
    }

    /**
     * This method finds the smallest shipping free limit.
     *
     * It iterates through the matched prices and looks for each price if the rule_id is good, the price is zero
     * and quantity_start is at least the total amount.
     *
     * @param array $prices
     * @param array $matchedPrices
     *
     * @param float $totalAmount
     *
     * @return float
     *
     * @psalm-param ShippingMethodPrice[] $prices
     * @psalm-param ShippingMethodPrice[] $matchedPrices
     */
    private function findLimit(array $prices, array $matchedPrices, float $totalAmount): float
    {
        /**
         * Ensure that the lowest price is at the top, otherwise the method will not return the smallest shipping free limit.
         *
         * @var array<array{quantity_end: string, rule_id: string}> $matchedPrices
         */
        usort(
            $matchedPrices,
            /**
             * @param array{quantity_end: string} $a
             * @param array{quantity_end: string} $b
             *
             * @return int
             */
            static fn (array $a, array $b) => (int) $a['quantity_end'] - (int) $b['quantity_end']
        );

        foreach ($matchedPrices as $row) {
            foreach ($prices as $row2) {
                if (
                    $row2['rule_id'] !== $row['rule_id']
                    || (int) $row2['price'] > 0
                ) {
                    continue;
                }

                if (
                    $totalAmount <= 0
                    || (float) $row2['quantity_start'] > $totalAmount
                    || (
                        $totalAmount >= (float) $row2['quantity_start']
                        && ($row2['quantity_end'] === null || $totalAmount <= $row2['quantity_end'])
                    )
                ) {
                    return (float) $row2['quantity_start'];
                }
            }
        }

        return 0;
    }
}
