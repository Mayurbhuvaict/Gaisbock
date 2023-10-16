<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Core\Content\Product\SalesChannel\RecentlyViewedProducts;

use Acris\SuggestedProducts\Components\RecentlyViewedProductsStruct;

class RecentlyViewedProductsResult extends RecentlyViewedProductsStruct
{
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
