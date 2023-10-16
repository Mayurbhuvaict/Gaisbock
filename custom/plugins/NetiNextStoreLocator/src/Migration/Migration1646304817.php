<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1646304817 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_646_304_817;
    }

    public function update(Connection $connection): void
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `neti_sl_filter` (
                `id` BINARY(16) NOT NULL,
                `active` TINYINT(1) NOT NULL DEFAULT \'0\',
                `value_type` INT(11) NOT NULL,
                `display_type` INT(11) NOT NULL,
                `position` INT(11) NOT NULL,
                `custom_field_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.neti_sl_filter.custom_field_id` (`custom_field_id`),
                CONSTRAINT `fk.neti_sl_filter.custom_field_id` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `neti_sl_filter_translation` (
                `title` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `neti_sl_filter_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`neti_sl_filter_id`,`language_id`),
                KEY `fk.neti_sl_filter_translation.neti_sl_filter_id` (`neti_sl_filter_id`),
                KEY `fk.neti_sl_filter_translation.language_id` (`language_id`),
                CONSTRAINT `fk.neti_sl_filter_translation.neti_sl_filter_id` FOREIGN KEY (`neti_sl_filter_id`) REFERENCES `neti_sl_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.neti_sl_filter_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `neti_sl_filter_tag` (
                `filter_id` BINARY(16) NOT NULL,
                `tag_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`filter_id`,`tag_id`),
                KEY `fk.neti_sl_filter_tag.filter_id` (`filter_id`),
                KEY `fk.neti_sl_filter_tag.tag_id` (`tag_id`),
                CONSTRAINT `fk.neti_sl_filter_tag.filter_id` FOREIGN KEY (`filter_id`) REFERENCES `neti_sl_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.neti_sl_filter_tag.tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
