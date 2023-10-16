<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Storefront\Page\RecentlyViewed;

use Acris\SuggestedProducts\Core\Content\Product\SalesChannel\RecentlyViewedProducts\AbstractRecentlyViewedProductsRoute;
use Shopware\Core\Content\Category\Exception\CategoryNotFoundException;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\Price\AbstractProductPriceCalculator;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RecentlyViewedPageLoader
{
    /**
     * @var GenericPageLoaderInterface
     */
    private $genericLoader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var AbstractRecentlyViewedProductsRoute
     */
    private $recentlyViewedProductsRoute;

    /**
     * @var AbstractProductPriceCalculator
     */
    private $calculator;

    public function __construct(
        GenericPageLoaderInterface $genericLoader,
        EventDispatcherInterface $eventDispatcher,
        AbstractRecentlyViewedProductsRoute $recentlyViewedProductsRoute,
        AbstractProductPriceCalculator $calculator
    ) {
        $this->genericLoader = $genericLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->recentlyViewedProductsRoute = $recentlyViewedProductsRoute;
        $this->calculator = $calculator;
    }

    /**
     * @throws CategoryNotFoundException
     * @throws InconsistentCriteriaIdsException
     * @throws MissingRequestParameterException
     */
    public function load(Request $request, SalesChannelContext $salesChannelContext, string $productId): ?RecentlyViewedPage
    {
        $page = $this->genericLoader->load($request, $salesChannelContext);
        $page = RecentlyViewedPage::createFrom($page);

        if ($page->getMetaInformation()) {
            $page->getMetaInformation()->setRobots('noindex,follow');
        }

        $productIds = $this->getProductIdsFromCookie($request, $salesChannelContext, $productId);

        if ($productIds) {
            $criteria = new Criteria();
            $criteria->setIds($productIds);

            $result = $this->recentlyViewedProductsRoute->load($request, $salesChannelContext, $criteria)->getResult()->getProducts();
            $this->calculator->calculate($result, $salesChannelContext);

            $page->setProducts($result);

            $this->eventDispatcher->dispatch(
                new RecentlyViewedPageLoadedEvent($page, $salesChannelContext, $request)
            );

            return $page;
        }

        return null;
    }

    private function getProductIdsFromCookie(Request $request, SalesChannelContext $salesChannelContext, string $productId): ?array
    {
        if ($productIdsInCookies = $request->cookies->get('acris_recently_viewed')) {
            $productIds = \explode("|", $productIdsInCookies);
        }

        $filterCurrentProduct = function ($item) use ($productId) {
            return $item != $productId;
        };

        $validateUUID = function ($item) {
            return UUID::isValid($item);
        };

        if (!empty($productIds)) {
            $productIds = array_map('trim', $productIds);

            $filtered = \array_filter($productIds, $filterCurrentProduct);

            return \array_filter($filtered, $validateUUID);
        }

        return null;
    }
}
