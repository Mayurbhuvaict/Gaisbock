<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573045796AddGeoColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_045_796;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'latitude', 'ADD COLUMN `%s` double AFTER `city`');
        $helper->add('neti_store_locator', 'longitude', 'ADD COLUMN `%s` double AFTER `city`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
