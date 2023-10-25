<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Storefront\Controller;

use NetInventors\NetiNextFreeDelivery\Components\ShippingFreeService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Route(defaults: [ '_routeScope' => [ 'storefront' ] ])]
class FreeDeliveryController extends StorefrontController
{
    public function __construct(
        private readonly ShippingFreeService $shippingFreeService
    ) {
    }

    #[Route(path: '/FreeDelivery/snippet', name: 'frontend.free_delivery.snippet', defaults: [ 'XmlHttpRequest' => true ], methods: [ 'GET' ])]
    public function index(SalesChannelContext $context): Response
    {
        $result = $this->shippingFreeService->calculateShippingCostsDifference($context);

        return $this->renderStorefront(
            'storefront/free_delivery/snippet.html.twig',
            [
                'result' => $result,
            ]
        );
    }
}
