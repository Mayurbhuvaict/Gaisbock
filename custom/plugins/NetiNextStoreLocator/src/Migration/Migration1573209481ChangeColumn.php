<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573209481ChangeColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_209_481;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->update('neti_store_locator', 'description', 'MODIFY COLUMN `%s` LONGTEXT NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
