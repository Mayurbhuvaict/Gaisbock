<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Route;

use NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail\StoreDetailPageLoader;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['store-api'], '_contextTokenRequired' => true ])]
class GetStoreRoute
{
    public function __construct(
        private readonly StoreDetailPageLoader $pageLoader
    ) {
    }

    /**
     * @Since("4.1.0")
     */
    #[Route(path: '/store-api/store-locator/get-store/{id}', name: 'store-api.store_locator.get_store', methods: ['GET'])]
    public function getStore(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        $page = $this->pageLoader->load($request, $salesChannelContext);

        return new JsonResponse(
            [
                'store'       => $page->getStore(),
                'config'      => $page->getConfig(),
                'htmlContent' => $page->getHtmlContent(),
                'contactForm' => [
                    'fields'         => array_values($page->getContactFormFields()),
                    'subjectOptions' => $page->getContactSubjectOptions(),
                ],
            ]
        );
    }
}
