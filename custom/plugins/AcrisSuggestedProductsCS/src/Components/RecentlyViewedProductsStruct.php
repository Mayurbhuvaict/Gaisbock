<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Components;

use Shopware\Core\Framework\Struct\Struct;

class RecentlyViewedProductsStruct extends Struct
{
    public const EXTENSION_KEY = 'acris_recently_viewed_products';

    private array $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }
}
