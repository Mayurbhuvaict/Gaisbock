<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Storefront\Controller;

use Acris\SuggestedProducts\Storefront\Page\RecentlyViewed\RecentlyViewedPageLoader;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class RecentlyViewedController extends StorefrontController
{
    private RecentlyViewedPageLoader $recentlyViewedPageLoader;

    public function __construct(RecentlyViewedPageLoader $recentlyViewedPageLoader)
    {
        $this->recentlyViewedPageLoader = $recentlyViewedPageLoader;
    }

    #[Route(path: '/recently-viewed', name: 'frontend.acris.suggested.products.recentlyviewed', options: ['seo' => false], defaults: ['XmlHttpRequest' => true], methods: ['POST'])]
    public function recentlyViewed(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $productId = $request->get('productId');
        $config = $request->get('config');
        $page = $this->recentlyViewedPageLoader->load($request, $salesChannelContext, $productId);
        if ($page) {
            return $this->renderStorefront('@Storefront/storefront/component/recently-viewed-products/recently-viewed-products.html.twig', ['page' => $page, 'config' => $config]);
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
