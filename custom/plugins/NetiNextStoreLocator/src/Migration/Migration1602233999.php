<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1602233999 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_602_233_999;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'country_state_id', 'ADD COLUMN `%s` BINARY(16) AFTER `country_id`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
