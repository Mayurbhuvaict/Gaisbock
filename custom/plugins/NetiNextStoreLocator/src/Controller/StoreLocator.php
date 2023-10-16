<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Controller;

use NetInventors\NetiNextStoreLocator\Components\GeoLocation\GeoLocation;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Address;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreBusinessHour\StoreBusinessHourEntity;
use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Route(defaults: [ '_routeScope' => [ 'api' ] ])]
class StoreLocator extends AbstractController
{
    public function __construct(
        private readonly PluginConfigStruct $pluginConfig,
        private readonly EntityRepository   $storeRepository,
        private readonly GeoLocation        $geoLocation
    ) {
    }

    #[Route(path: '/api/_action/neti-store-locator/ids', name: 'api.action.neti-store-locator.get_ids', methods: [ 'GET' ])]
    public function getIds(Context $context): JsonResponse
    {
        $criteria = new Criteria();
        $queries  = [
            new EqualsFilter('latitude', null),
            new EqualsFilter('longitude', null),
        ];

        if ($this->pluginConfig->isGoogleApiTimezoneEnabled()) {
            $queries[] = new EqualsFilter('timezone', null);
        }

        $criteria->addFilter(
            new MultiFilter(
                MultiFilter::CONNECTION_OR,
                $queries
            )
        );

        $ids = $this->storeRepository->search($criteria, $context)->getIds();

        return new JsonResponse(
            [
                'data'  => $ids,
                'total' => count($ids),
            ]
        );
    }

    #[Route(path: '/api/_action/neti-store-locator/locateIds', name: 'api.action.neti-store-locator.locate_ids', methods: [ 'POST' ])]
    public function locateIds(Request $request, Context $context): JsonResponse
    {
        /** @var string[] $ids */
        $ids = (array) json_decode($request->getContent(), true);

        if (empty($ids)) {
            return new JsonResponse(
                [
                    'success' => false,
                ]
            );
        }

        $data = [];

        foreach ($ids as $id) {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('id', $id));

            /** @var StoreEntity|null $store */
            $store = $this->storeRepository->search($criteria, $context)->first();

            if ($store instanceof StoreEntity) {
                $address = Address::createFrom($store);

                try {
                    $coords = $this->geoLocation->getCoords($address);
                } catch (\Exception $ex) {
                    return new JsonResponse(
                        [
                            'success' => false,
                            'message' => $ex->getMessage(),
                        ]
                    );
                }

                $timezone = $this->geoLocation->getTimezone($coords);

                $store->setLatitude($coords->getLatitude());
                $store->setLongitude($coords->getLongitude());

                $data[] = [
                    'id'        => $store->getId(),
                    'latitude'  => $store->getLatitude(),
                    'longitude' => $store->getLongitude(),
                    'timezone'  => $timezone,
                ];
            }
        }

        $this->storeRepository->update($data, $context);

        return new JsonResponse(
            [
                'success'          => true,
                'hasTimezoneError' => $this->geoLocation->hasTimezoneError(),
            ]
        );
    }

    #[Route(path: '/api/_action/neti-store-locator/locate', name: 'api.action.neti-store-locator.locate', methods: [ 'POST' ])]
    public function locate(Request $request): JsonResponse
    {
        /**
         * @var array{
         *     street: string|null,
         *     streetNumber: string|null,
         *     zipCode: string|null,
         *     city: string|null,
         *     countryId: string|null
         * } $data
         */
        $data = (array) json_decode($request->getContent(), true);

        $address = new Address();
        $address->setStreet($data['street'] ?? '');
        $address->setStreetNumber($data['streetNumber'] ?? '');
        $address->setZipCode($data['zipCode'] ?? '');
        $address->setCity($data['city'] ?? '');
        $address->setCountryId($data['countryId'] ?? '');

        try {
            $coords = $this->geoLocation->getCoords($address);

            return new JsonResponse(
                [
                    'success' => true,
                    'data'    => [
                        'latitude'  => $coords->getLatitude(),
                        'longitude' => $coords->getLongitude(),
                    ],
                ]
            );
        } catch (\Exception $ex) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => $ex->getMessage(),
                ]
            );
        }
    }

    #[Route(path: '/api/_action/neti-store-locator/config', name: 'api.action.neti-store-locator.config', methods: [ 'GET' ])]
    public function getPluginConfig(): JsonResponse
    {
        /** @var SystemConfigService $config */
        $config = $this->container->get(SystemConfigService::class);

        /**
         * @psalm-suppress InternalMethod The method is not being called from storefront or store api, we're in admin.
         * @var array<string, mixed> $data
         */
        $data = $config->getDomain('NetiNextStoreLocator.config');

        /**
         * @var string|bool|int|float $value
         */
        foreach ($data as $key => $value) {
            unset($data[$key]);

            $key = substr($key, strlen('NetiNextStoreLocator.config.'));

            $data[$key] = $value;
        }

        return new JsonResponse(
            $data
        );
    }

    #[Route(path: '/api/_action/neti-store-locator/deleteBusinessHours', name: 'api.action.neti-store-locator.delete_business_hours', methods: [ 'POST' ])]
    public function deleteBusinessHours(Request $request, Context $context): JsonResponse
    {
        $storeId = $request->request->getAlnum('storeId');

        if ('' === $storeId) {
            throw new \InvalidArgumentException('Please pass a valid storeId');
        }

        /** @var EntityRepository $storeBusinessHourRepository */
        $storeBusinessHourRepository = $this->container->get('neti_store_business_hour.repository');

        /** @var EntityRepository $businessHourRepository */
        $businessHourRepository = $this->container->get('neti_business_hour.repository');
        $businessHourIds        = [];

        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('storeId', $storeId));

        /**
         * False alert, that only occurs in gitlab.
         *
         * @psalm-suppress MixedAssignment
         */
        $storeBusinessHours = $storeBusinessHourRepository->search($criteria, $context)->getElements();

        /** @var StoreBusinessHourEntity $storeBusinessHour */
        foreach ($storeBusinessHours as $storeBusinessHour) {
            $businessHourIds[] = [ 'id' => $storeBusinessHour->getBusinessHourId() ];
        }

        if ([] !== $businessHourIds) {
            $businessHourRepository->delete($businessHourIds, $context);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
