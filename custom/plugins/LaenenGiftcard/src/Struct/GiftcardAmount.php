<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Struct;

use Shopware\Core\Framework\Struct\Struct;

class GiftcardAmount extends Struct
{
    public function __construct(
        private float $amount
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
