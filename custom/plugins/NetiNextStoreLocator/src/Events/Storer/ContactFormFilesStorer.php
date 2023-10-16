<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Events\Storer;

use NetInventors\NetiNextStoreLocator\Events\StoreLocatorFilesAware;
use Shopware\Core\Content\Flow\Dispatching\StorableFlow;
use Shopware\Core\Content\Flow\Dispatching\Storer\FlowStorer;
use Shopware\Core\Framework\Event\FlowEventAware;

class ContactFormFilesStorer extends FlowStorer
{
    public function store(FlowEventAware $event, array $stored): array
    {
        if (
            $event instanceof StoreLocatorFilesAware
            && false === array_key_exists(StoreLocatorFilesAware::STORE_KEY, $stored)
        ) {
            $stored[StoreLocatorFilesAware::STORE_KEY] = $event->getFiles();
        }

        return $stored;
    }

    public function restore(StorableFlow $storable): void
    {
        if (!$storable->hasStore(StoreLocatorFilesAware::STORE_KEY)) {
            return;
        }

        $storable->setData(
            StoreLocatorFilesAware::STORE_KEY,
            $storable->getStore(StoreLocatorFilesAware::STORE_KEY)
        );
    }
}
