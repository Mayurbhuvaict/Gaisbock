<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Subscriber;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\GeoLocation;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Address;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Coordinates;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Struct\CheckoutFinishStoresStruct;
use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use NetInventors\NetiNextStoreLocator\Struct\StoreDistanceStruct;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryCollection;
use Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\Checkout\Finish\CheckoutFinishPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckoutFinishPageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly PluginConfigStruct     $pluginConfig,
        private readonly GeoLocation            $geoLocation,
        private readonly Connection             $connection,
        private readonly EntityRepository       $storeRepository,
        private readonly LoggerInterface        $logger,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutFinishPageLoadedEvent::class => 'onCheckoutFinishPageLoaded',
        ];
    }

    public function onCheckoutFinishPageLoaded(CheckoutFinishPageLoadedEvent $event): void
    {
        if (
            false === $this->pluginConfig->isActive()
            || false === $this->pluginConfig->isShowStoresOnOrderFinish()
        ) {
            return;
        }

        $page  = $event->getPage();
        $order = $page->getOrder();

        try {
            $coordinates = $this->getCoordinates($order);

            if ($coordinates instanceof Coordinates) {
                $stores = $this->getNearestStores($coordinates, $event->getSalesChannelContext());

                $struct = new CheckoutFinishStoresStruct($stores);
                $page->addExtension('netiStores', $struct);
            }
        } catch (\Exception $ex) {
            $this->logger->error(
                'Unable to fetch nearest stores for the order finish page',
                [
                    'message' => $ex->getMessage(),
                ]
            );
        }
    }

    private function getNearestStores(Coordinates $coordinates, SalesChannelContext $salesChannelContext): array
    {
        $context = $salesChannelContext->getContext();
        $limit   = max(0, $this->pluginConfig->getOrderFinishStoreCount());
        $sql     = "
            SELECT
              LOWER(HEX(s.id)) AS id,
              (
                :unit * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(s.latitude)) *
                COS(RADIANS(s.longitude) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(s.latitude)))
              ) AS distance
            FROM neti_store_locator s
            LEFT JOIN neti_store_sales_channel c ON c.store_id = s.id
            WHERE active = 1
              AND HEX(c.sales_channel_id) = :salesChannelId
              AND s.latitude IS NOT NULL
              AND s.longitude IS NOT NULL
            ORDER BY distance ASC
            LIMIT $limit
        ";

        $stores = $this->connection->fetchAllKeyValue(
            $sql,
            [
                'unit'           => $this->pluginConfig->getDistanceUnit() === 'km' ? 6371 : 3959,
                'lat'            => $coordinates->getLatitude(),
                'lng'            => $coordinates->getLongitude(),
                'salesChannelId' => $salesChannelContext->getSalesChannelId(),
            ]
        );

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         *
         * This is the correct way to search for a IDs
         */
        $criteria = new Criteria(array_keys($stores));
        $result   = $this->storeRepository->search($criteria, $context)->getElements();

        /** @var StoreEntity $store */
        foreach ($result as $store) {
            $distanceStruct = new StoreDistanceStruct((float) $stores[$store->getId()]);

            $store->addExtension('netiDistance', $distanceStruct);
        }

        return $result;
    }

    private function getCoordinates(OrderEntity $order): ?Coordinates
    {
        $address = null;

        if ($this->pluginConfig->getOrderFinishAddressType() === PluginConfigStruct::ORDER_FINISH_ADDRESS_TYPE_SHIPPING) {
            $delivery   = null;
            $deliveries = $order->getDeliveries();

            if ($deliveries instanceof OrderDeliveryCollection) {
                $delivery = $deliveries->first();
            }

            if ($delivery instanceof OrderDeliveryEntity) {
                $address = $delivery->getShippingOrderAddress();
            }
        } else {
            $address = $order->getBillingAddress();
        }

        if (null === $address) {
            return null;
        }

        $cacheKey = 'neti_store_locator_address_coordinates_' . $address->getId();
        $item     = $this->cache->getItem($cacheKey);

        if ($item->isHit()) {
            /** @var Coordinates|mixed $coordinates */
            $coordinates = $item->get();

            if ($coordinates instanceof Coordinates) {
                return $coordinates;
            }
        }

        $addressStruct = new Address();
        $addressStruct->setStreet($address->getStreet());
        $addressStruct->setZipCode($address->getZipcode());
        $addressStruct->setCity($address->getCity());
        $addressStruct->setCountryId($address->getCountryId());

        $coordinates = $this->geoLocation->getCoords($addressStruct);

        $item->set($coordinates);
        $this->cache->save($item);

        return $coordinates;
    }
}
