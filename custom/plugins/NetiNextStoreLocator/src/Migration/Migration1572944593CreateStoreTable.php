<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1572944593CreateStoreTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_572_944_593;
    }

    public function update(Connection $connection): void
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `neti_store_locator` (
                `id` BINARY(16) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `street` VARCHAR(255) NOT NULL,
                `street_number` VARCHAR(255) NOT NULL,
                `zip_code` VARCHAR(32) NOT NULL,
                `city` VARCHAR(255) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3),
                PRIMARY KEY (`id`)
            )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        ';

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
