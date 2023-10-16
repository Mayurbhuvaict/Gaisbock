<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Subscriber;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use NetInventors\NetiNextStoreLocator\Service\ImportExport\StoreImport;
use Shopware\Core\Content\ImportExport\Event\ImportExportBeforeImportRecordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImportExportSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly StoreImport $storeImport
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ImportExportBeforeImportRecordEvent::class => 'onBeforeImport',
        ];
    }

    public function onBeforeImport(ImportExportBeforeImportRecordEvent $event): void
    {
        if (StoreDefinition::ENTITY_NAME === $event->getConfig()->get('sourceEntity')) {
            /**
             * @psalm-suppress MixedArgumentTypeCoercion
             */
            $data = $this->storeImport->prepareDataForImport(
                $event->getRecord(),
                $event->getContext()
            );

            $event->setRecord($data);
        }
    }
}
