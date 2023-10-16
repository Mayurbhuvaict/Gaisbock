<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Core\Content\Product\SalesChannel\RecentlyViewedProducts;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * This route is used for the product listing at the manufacturer search result
 */
abstract class AbstractRecentlyViewedProductsRoute
{
    abstract public function getDecorated(): AbstractRecentlyViewedProductsRoute;

    abstract public function load(Request $request, SalesChannelContext $salesChannelContext, Criteria $criteria): RecentlyViewedProductsRouteResponse;
}
