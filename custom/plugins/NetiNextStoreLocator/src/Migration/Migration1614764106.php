<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1614764106 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_614_764_106;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator_translation', 'opening_times', 'ADD COLUMN `%s` TEXT NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
