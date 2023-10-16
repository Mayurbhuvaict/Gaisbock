<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Subscriber;

use NetInventors\NetiNextStoreLocator\Components\EntityFilter;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\GeoLocation;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Address;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Storefront\Framework\Seo\SeoUrlRoute\StorePageSeoUrlRoute;
use Psr\Log\LoggerInterface;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityFilter     $entityFilter,
        private readonly GeoLocation      $geoLocation,
        private readonly EntityRepository $repository,
        private readonly LoggerInterface  $logger,
        private readonly SeoUrlUpdater    $seoUrlUpdater
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'neti_store_locator.written' => 'onWritten',
        ];
    }

    public function onWritten(EntityWrittenEvent $event): void
    {
        /** @var list<string> $ids */
        $ids = $event->getIds();

        $this->seoUrlUpdater->update(StorePageSeoUrlRoute::ROUTE_NAME, $ids);

        $ids = $this->entityFilter->getAffectedIDs($event);

        if (!empty($ids)) {
            /**
             * @psalm-suppress MixedArgumentTypeCoercion
             *
             * This is the correct way to search for a specific ID
             */
            $criteria = new Criteria($ids);
            $entities = $this->repository->search($criteria, $event->getContext());
            $updates  = [];

            /** @var StoreEntity $entity */
            foreach ($entities as $entity) {
                $address = Address::createFrom($entity);

                try {
                    $coords   = $this->geoLocation->getCoords($address);
                    $timezone = $this->geoLocation->getTimezone($coords);

                    $updates[] = [
                        'id'            => $entity->getId(),
                        'longitude'     => $coords->getLongitude(),
                        'latitude'      => $coords->getLatitude(),
                        'googlePlaceID' => $coords->getPlaceId(),
                        'timezone'      => $timezone,
                    ];
                } catch (\Exception $ex) {
                    $this->logger->notice(
                        'Unable to get the coordinates for the address of a store',
                        [
                            'exception' => $ex->getMessage(),
                        ]
                    );
                }
            }

            if (!empty($updates)) {
                $this->repository->update($updates, $event->getContext());
            }
        }
    }
}
