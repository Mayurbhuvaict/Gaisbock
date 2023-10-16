<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Controller;

use NetInventors\NetiNextStoreLocator\Service\StoreFilterValueService;
use Shopware\Core\Framework\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Route(defaults: ['_routeScope' => ['api']])]
class Filter extends AbstractController
{
    public function __construct(
        private readonly StoreFilterValueService $filterValueService
    ) {
    }

    #[Route(path: '/api/_action/neti-store-locator/build-filter-values', name: 'api.action.neti-store-locator.build_filter_values', methods: ['POST'])]
    public function build(Request $request, Context $context): JsonResponse
    {
        $filterId = (string) $request->request->get('id');

        $this->filterValueService->build($filterId, $context);

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
