<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope" = {"storefront"}})
 */
class FreeShippingBarController extends StorefrontController {
    public function __construct(private readonly OffcanvasCartPageLoader $pageLoader)
    {
    }

    #[Route(path: '/widgets/neno-marekting-essentials/free-shipping-bar/info', name: 'frontend.neno-marketing-essentials.free-shipping-bar.info', methods: ['GET'], defaults: ['XmlHttpRequest' => true])]
    public function info(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->pageLoader->load($request, $context);

        return $this->renderStorefront('@NenoMarketingEssentials/storefront/widgets/free-shipping-bar-info.html.twig', ['page' => $page]);
    }
}
