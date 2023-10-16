<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1625817765 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_625_817_765;
    }

    public function update(Connection $connection): void
    {
        $query = '
            ALTER TABLE `neti_store_locator`
            CHANGE `street_number` `street_number` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
        ';

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
