<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1576503670RenameColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_576_503_670;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->update('neti_store_locator', 'opening_times', 'CHANGE `%s` `additional_information` TEXT');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
