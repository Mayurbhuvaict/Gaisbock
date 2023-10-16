<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1630049295AddExternalIdColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_630_049_295;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'external_id', 'ADD COLUMN `%s` VARCHAR(255) NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
