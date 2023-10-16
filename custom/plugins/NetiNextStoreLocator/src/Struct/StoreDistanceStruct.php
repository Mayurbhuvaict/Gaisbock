<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Struct;

use Shopware\Core\Framework\Struct\Struct;

class StoreDistanceStruct extends Struct
{
    public function __construct(
        protected float $value
    ) {
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
