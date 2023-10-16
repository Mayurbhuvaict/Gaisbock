<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Core\Content\Product\SalesChannel\RecentlyViewedProducts;

use Acris\SuggestedProducts\Core\Content\Product\Events\RecentlyViewedProductsResultEvent;
use OpenApi\Annotations as OA;
use Shopware\Core\Content\Product\Events\ProductSearchCriteriaEvent;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingLoader;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route(defaults={"_routeScope" = {"storefront"}})
 */
class RecentlyViewedProductsRoute extends AbstractRecentlyViewedProductsRoute
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProductListingLoader
     */
    private $productListingLoader;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ProductListingLoader $productListingLoader
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->productListingLoader = $productListingLoader;
    }

    public function getDecorated(): AbstractRecentlyViewedProductsRoute
    {
        throw new DecorationPatternException(self::class);
    }

    public function load(Request $request, SalesChannelContext $salesChannelContext, Criteria $criteria = null): RecentlyViewedProductsRouteResponse
    {

        $collection = $this->productListingLoader->load($criteria, $salesChannelContext)->getEntities();
        $this->eventDispatcher->dispatch(
            new ProductSearchCriteriaEvent($request, $criteria, $salesChannelContext)
        );
        $productCollectionElements = $collection->getElements();
        $result = new RecentlyViewedProductsResult($productCollectionElements);
        $this->eventDispatcher->dispatch(
            new RecentlyViewedProductsResultEvent($request, $result, $salesChannelContext)
        );

        return new RecentlyViewedProductsRouteResponse($result);
    }
}
