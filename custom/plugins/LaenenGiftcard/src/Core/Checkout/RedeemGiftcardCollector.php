<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout;

use Laenen\Giftcard\Service\GiftcardGateway;
use Laenen\Giftcard\Struct\AppliedGiftcardStruct;
use Laenen\Giftcard\Struct\AppliedGiftcardStructCollection;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Laenen\Giftcard\Struct\GiftcardStructCollection;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Promotion\Cart\Extension\CartExtension;
use Shopware\Core\Checkout\Promotion\Cart\PromotionProcessor;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class RedeemGiftcardCollector implements CartDataCollectorInterface, CartProcessorInterface
{
    public const GIFTCARDS = 'lae-giftcards';

    public function __construct(
        private GiftcardGateway $giftcardGateway
    ) {
    }

    public function collect(
        CartDataCollection $data,
        Cart $original,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $applied = $original->getExtension(self::GIFTCARDS);
        $existingCodes = $applied instanceof AppliedGiftcardStructCollection ? $applied->getCodes() : [];

        $cartCodes = $original->getLineItems()->filterType(PromotionProcessor::LINE_ITEM_TYPE)->getReferenceIds();
        $allCodes = array_unique(array_values(array_filter(array_map(function (string $code) {
            return self::filterCode($code);
        }, array_merge($existingCodes, $cartCodes)))));

        if (!count($allCodes)) {
            $original->removeExtension(self::GIFTCARDS);
            $data->remove(self::class);

            return;
        }

        $giftcards = $this->giftcardGateway->find($allCodes, $context);

        $data->set(self::class, $giftcards);

        $cartExtension = $original->getExtension(CartExtension::KEY);
        foreach ($original->getLineItems()->filterType(PromotionProcessor::LINE_ITEM_TYPE) as $lineItem) {
            $code = self::filterCode($lineItem->getReferencedId());

            if (!$giftcards->has($code)) {
                continue;
            }

            $lineItem->setRemovable(true);
            $original->remove($lineItem->getId());

            if ($cartExtension instanceof CartExtension) {
                $cartExtension->removeCode($lineItem->getReferencedId());
            }
        }
    }

    public function process(
        CartDataCollection $data,
        Cart $original,
        Cart $toCalculate,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        if (!$data->get(self::class)) {
            return;
        }

        $applied = new AppliedGiftcardStructCollection();
        $toCalculate->addExtension(self::GIFTCARDS, $applied);

        $cartTotalPrice = $toCalculate->getPrice()->getTotalPrice();

        /** @var GiftcardStructCollection $giftcards */
        $giftcards = $data->get(self::class);

        /** @var GiftcardStruct $giftcard */
        foreach ($giftcards as $giftcard) {
            $balance = $giftcard->getBalance();
            if ($balance > $cartTotalPrice) {
                $balance = $cartTotalPrice;
            }
            $cartTotalPrice -= $balance;

            $applied->add(AppliedGiftcardStruct::createFromGiftcardStruct($giftcard, $balance));
        }
    }

    private static function filterCode(string $code): string
    {
        return trim(str_replace(' ', '', $code));
    }
}
