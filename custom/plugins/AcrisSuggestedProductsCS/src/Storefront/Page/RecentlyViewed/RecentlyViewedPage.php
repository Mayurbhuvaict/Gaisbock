<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Storefront\Page\RecentlyViewed;

use Shopware\Storefront\Page\Page;

class RecentlyViewedPage extends Page
{
    /**
     * @var array
     */
    protected $productIds;

    /**
     * @var array
     */
    protected $products;

    /**
     * @return array
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }

    /**
     * @param array $productIds
     */
    public function setProductIds(string $productIds): void
    {
        $this->productIds = $productIds;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $listing): void
    {
        $this->products = $listing;
    }
}
