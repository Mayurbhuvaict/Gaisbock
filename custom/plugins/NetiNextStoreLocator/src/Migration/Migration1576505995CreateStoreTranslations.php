<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1576505995CreateStoreTranslations extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_576_505_995;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `neti_store_locator_translation` (
    `description` LONGTEXT NULL,
    `additional_information` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `neti_store_locator_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`neti_store_locator_id`,`language_id`),
    KEY `fk.neti_store_locator_translation.neti_store_locator_id` (`neti_store_locator_id`),
    KEY `fk.neti_store_locator_translation.language_id` (`language_id`),
    CONSTRAINT `fk.neti_store_locator_translation.neti_store_locator_id` FOREIGN KEY (`neti_store_locator_id`) REFERENCES `neti_store_locator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_locator_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOL;

        $connection->executeStatement($sql);

        $helper = new ColumnHelper($connection);
        $helper->update('neti_store_locator', 'description', 'DROP COLUMN `%s`');
        $helper->update('neti_store_locator', 'additional_information', 'DROP COLUMN `%s`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
