<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573140186AddColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_140_186;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'sales_channel_id', 'ADD COLUMN `%s` BINARY(16) NOT NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
