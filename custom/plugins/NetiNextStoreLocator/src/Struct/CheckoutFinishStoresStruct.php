<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Struct;

use Shopware\Core\Framework\Struct\Struct;

class CheckoutFinishStoresStruct extends Struct
{
    public function __construct(
        private readonly array $stores
    ) {
    }

    public function getStores(): array
    {
        return $this->stores;
    }
}
