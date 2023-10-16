<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Controller;

use NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail\StoreDetailPageLoader;
use NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing\StoreListingPageLoader;
use NetInventors\NetiNextStoreLocator\Storefront\Route\ContactRoute;
use NetInventors\NetiNextStoreLocator\Storefront\Route\GetStoresRoute;
use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Route(defaults: [ '_routeScope' => [ 'storefront' ] ])]
class StoreLocatorController extends StorefrontController
{
    public function __construct(
        private readonly StoreDetailPageLoader  $storeDetailPageLoader,
        private readonly StoreListingPageLoader $storeListingPageLoader,
        private readonly PluginConfigStruct     $pluginConfig,
        private readonly ContactRoute           $contactRoute,
        private readonly GetStoresRoute         $getStoresRoute
    ) {
    }

    #[Route(path: '/StoreLocator', name: 'frontend.store_locator.index', methods: [ 'GET' ])]
    public function index(Request $request, SalesChannelContext $context): Response
    {
        if (false === $this->pluginConfig->isActive()) {
            throw new NotFoundHttpException();
        }

        $page = $this->storeListingPageLoader->load($request, $context);

        return $this->renderStorefront(
            'storefront/store_locator/index.twig',
            [
                'page' => $page,
            ]
        );
    }

    /**
     * @HttpCache()
     */
    #[Route(path: '/StoreLocator/getStores', name: 'frontend.store_locator.get_stores', methods: [ 'GET' ], defaults: [ 'XmlHttpRequest' => true ])]
    public function getStores(Request $request, SalesChannelContext $context): JsonResponse
    {
        if (false === $this->pluginConfig->isActive()) {
            throw new NotFoundHttpException();
        }

        return $this->getStoresRoute->getStores($request, $context);
    }

    #[Route(path: '/StoreLocator/detail/{id}', name: 'frontend.store_locator.detail', methods: [ 'GET' ])]
    public function detail(Request $request, SalesChannelContext $context): Response
    {
        if (false === $this->pluginConfig->isActive()) {
            throw new NotFoundHttpException();
        }
        $page = $this->storeDetailPageLoader->load($request, $context);

        return $this->renderStorefront(
            'storefront/store_locator/detail.twig',
            [
                'page' => $page,
            ]
        );
    }

    /**
     * @deprecated Use `\NetInventors\NetiNextStoreLocator\Storefront\Route\ContactRoute::contact` instead.
     */
    #[Route(path: '/StoreLocator/contact', name: 'frontend.store_locator.contact', defaults: [ 'XmlHttpRequest' => true ], methods: [ 'POST' ])]
    public function contact(Request $request, SalesChannelContext $context): JsonResponse
    {
        if (false === $this->pluginConfig->isActive()) {
            throw new NotFoundHttpException();
        }

        return $this->contactRoute->contact($request, $context);
    }

    #[Route(path: '/StoreLocator/cms', name: 'frontend.store_locator.cms', methods: [ 'POST' ])]
    public function cms(Request $request): RedirectResponse
    {
        $data             = $request->request->all();
        $redirectResponse = $this->redirectToRoute('frontend.store_locator.index');
        $url              = $redirectResponse->getTargetUrl() . '?search=' . (string) $data['search'];

        /** @var string|null $additionalParams */
        $additionalParams = $data['additionalParams'] ?? null;

        if (is_string($additionalParams) && '' !== $additionalParams) {
            $url .= '&' . $additionalParams;
        }

        $redirectResponse->setTargetUrl($url);

        return $redirectResponse;
    }
}
