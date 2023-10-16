<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Route;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Events\GetStoresEvent;
use NetInventors\NetiNextStoreLocator\Service\StoreBusinessHoursService;
use NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing\StoreListingPageLoader;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['store-api'], '_contextTokenRequired' => true ])]
class GetStoresRoute
{
    public function __construct(
        private readonly StoreListingPageLoader    $pageLoader,
        private readonly StoreBusinessHoursService $businessHoursService,
        private readonly EventDispatcherInterface  $eventDispatcher
    ) {
    }

    /**
     * @Since("4.1.0")
     */
    #[Route(path: '/store-api/store-locator/get-stores', name: 'store-api.store_locator.get_stores', methods: ['GET'])]
    public function getStores(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        $stores            = $this->pageLoader->getStores($request, $salesChannelContext);
        $context           = $salesChannelContext->getContext();
        $timeZones         = [];
        $customersTimezone = (string) $request->cookies->get('timezone');

        $this->eventDispatcher->dispatch(new GetStoresEvent($stores, $salesChannelContext));

        $timeZones[$customersTimezone] = new \DateTime(
            'now',
            new \DateTimeZone($customersTimezone)
        );

        /** @var StoreEntity $store */
        foreach ($stores->getElements() as $store) {
            $timeZone = $store->getTimezone();

            if (null === $timeZone) {
                $store->addArrayExtension('storeBusinessHours', []);

                continue;
            }

            if (!isset($timeZones[$timeZone])) {
                $timeZones[$timeZone] = new \DateTime(
                    'now',
                    new \DateTimeZone($timeZone)
                );
            }

            $storeId = $store->getId();

            $store->addArrayExtension(
                'storeBusinessHours',
                [
                    'businessHours' => $this->businessHoursService->getStoreBusinessHours(
                        $storeId,
                        $context
                    ),
                    'weekDays'      => $this->businessHoursService->getBusinessWeekdays($context),
                    'isStoreOpen'   => $this->businessHoursService->isStoreOpen(
                        $timeZones[$timeZone],
                        $storeId,
                        $context
                    ),
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => true,
                'total'   => $stores->count(),
                'data'    => $stores->getEntities(),
            ]
        );
    }
}
