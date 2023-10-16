<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1653310700 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_653_310_700;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add(
            'neti_store_locator',
            'timezone',
            'ADD `timezone` varchar(255) COLLATE \'utf8mb4_unicode_ci\' NULL AFTER `latitude`'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
