<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Laenen\Giftcard\Struct\AppliedGiftcardStructCollection;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\Price\AmountCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Cart\Processor;
use Shopware\Core\Checkout\Cart\Transaction\TransactionProcessor;
use Shopware\Core\Checkout\Cart\Validator;
use Shopware\Core\Framework\Script\Execution\ScriptExecutor;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class GiftcardProcessor extends Processor
{
    public function __construct(
        private Processor $decorated,
        Validator $validator,
        AmountCalculator $amountCalculator,
        TransactionProcessor $transactionProcessor,
        iterable $processors,
        iterable $collectors,
        ScriptExecutor $executor
    ) {
        parent::__construct($validator, $amountCalculator, $transactionProcessor, $processors, $collectors, $executor);
    }

    public function process(Cart $original, SalesChannelContext $context, CartBehavior $behavior): Cart
    {
        $cart = $this->decorated->process($original, $context, $behavior);

        $applied = $cart->getExtension(RedeemGiftcardCollector::GIFTCARDS);
        if (!$applied instanceof AppliedGiftcardStructCollection) {
            return $cart;
        }

        $cart->setPrice(new CartPrice(
            $cart->getPrice()->getNetPrice(),
            $cart->getPrice()->getTotalPrice() - $applied->getAppliedTotal(),
            $cart->getPrice()->getPositionPrice(),
            $cart->getPrice()->getCalculatedTaxes(),
            $cart->getPrice()->getTaxRules(),
            $cart->getPrice()->getTaxStatus(),
            $cart->getPrice()->getRawTotal() - $applied->getAppliedTotal(),
        ));

        return $cart;
    }
}
