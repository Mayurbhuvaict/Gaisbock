<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Route;

use NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing\StoreListingPageLoader;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['store-api'], '_contextTokenRequired' => true ])]
class ConfigRoute
{
    public function __construct(
        private readonly StoreListingPageLoader $pageLoader
    ) {
    }

    /**
     * @Since("4.1.0")
     */
    #[Route(path: '/store-api/store-locator/config', name: 'store-api.store_locator.config', methods: ['GET'])]
    public function config(Request $request, SalesChannelContext $context): JsonResponse
    {
        $page = $this->pageLoader->load($request, $context);

        return new JsonResponse(
            [
                'config'      => $page->getConfig(),
                'orderTypes'  => $page->getOrderTypes(),
                'countries'   => $page->getCountries(),
                'radiusList'  => $page->getRadiusList(),
                'contactForm' => [
                    'fields'         => array_values($page->getContactFormFields()),
                    'subjectOptions' => $page->getContactSubjectOptions(),
                ],
            ]
        );
    }
}
