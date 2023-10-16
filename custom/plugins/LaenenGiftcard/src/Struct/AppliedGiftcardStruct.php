<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Struct;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Shopware\Core\Framework\Struct\Struct;

class AppliedGiftcardStruct extends GiftcardStruct
{
    protected float $appliedAmount;

    public static function createFromGiftcardStruct(
        GiftcardStruct $giftcardStruct,
        float $appliedAmount
    ): self {
        $giftcard = self::createFrom($giftcardStruct);
        $giftcard->setAppliedAmount($appliedAmount);

        return $giftcard;
    }

    public function getAppliedAmount(): float
    {
        return $this->appliedAmount;
    }

    public function setAppliedAmount(float $appliedAmount): void
    {
        $this->appliedAmount = $appliedAmount;
    }
}
