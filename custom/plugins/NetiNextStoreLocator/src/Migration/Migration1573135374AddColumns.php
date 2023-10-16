<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573135374AddColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_135_374;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'phone', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'fax', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'url', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'email', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'opening_times', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'country_id', 'ADD COLUMN `%s` BINARY(16) NOT NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
