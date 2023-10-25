<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Struct;

use Shopware\Core\Framework\Struct\Struct;

class FreeDeliveryResult extends Struct
{
    private float $percentage;

    public function __construct(
        private readonly float $difference,
        private readonly float $targetValue
    ) {
        if ($targetValue > 0) {
            $this->percentage = ceil(($targetValue - $difference) / $targetValue * 100);
        } else {
            $this->percentage = 0;
        }
    }

    public function getDifference(): float
    {
        return $this->difference;
    }

    public function getTargetValue(): float
    {
        return $this->targetValue;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
