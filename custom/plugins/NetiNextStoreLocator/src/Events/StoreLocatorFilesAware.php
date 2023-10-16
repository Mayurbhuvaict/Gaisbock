<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Events;

use Shopware\Core\Framework\Event\FlowEventAware;

interface StoreLocatorFilesAware extends FlowEventAware
{
    public const STORE_KEY = 'neti_sl_files';

    public function getFiles(): array;
}
