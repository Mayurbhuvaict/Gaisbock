<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Core\Content\Product\SalesChannel\RecentlyViewedProducts;

use Shopware\Core\System\SalesChannel\StoreApiResponse;

class RecentlyViewedProductsRouteResponse extends StoreApiResponse
{
    /**
     * @var RecentlyViewedProductsResult
     */
    protected $object;

    public function __construct(RecentlyViewedProductsResult $object)
    {
        parent::__construct($object);
    }

    public function getResult(): RecentlyViewedProductsResult
    {
        return $this->object;
    }
}
