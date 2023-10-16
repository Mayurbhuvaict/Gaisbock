<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1680157673 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_680_157_673;
    }

    public function update(Connection $connection): void
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `neti_sl_filter_value` (
                `id` BINARY(16) NOT NULL,
                `filter_id` BINARY(16) NULL,
                `value` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.neti_sl_filter_value.filter_id` (`filter_id`),
                CONSTRAINT `fk.neti_sl_filter_value.filter_id` FOREIGN KEY (`filter_id`) REFERENCES `neti_sl_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
