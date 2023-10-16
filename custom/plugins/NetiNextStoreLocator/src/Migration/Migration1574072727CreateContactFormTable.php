<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1574072727CreateContactFormTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_574_072_727;
    }

    public function update(Connection $connection): void
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `neti_store_locator_contact_form` (
                `id` BINARY(16) NOT NULL,
                `active` TINYINT(1) NULL DEFAULT \'0\',
                `label` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `value` LONGTEXT NULL,
                `required` TINYINT(1) NULL DEFAULT \'0\',
                `position` INT(11) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
