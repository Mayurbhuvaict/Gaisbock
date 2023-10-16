<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1577972394CreateContactFormTranslations extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_577_972_394;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `neti_store_locator_contact_form_translation` (
    `label` VARCHAR(255) NOT NULL,
    `value` LONGTEXT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3),
    `neti_store_locator_contact_form_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`neti_store_locator_contact_form_id`,`language_id`),
    KEY `fk.neti_contact_form_translation.neti_contact_form_id` (`neti_store_locator_contact_form_id`),
    KEY `fk.neti_store_locator_contact_form_translation.language_id` (`language_id`),
    CONSTRAINT `fk.neti_contact_form_translation.neti_contact_form_id` FOREIGN KEY (`neti_store_locator_contact_form_id`) REFERENCES `neti_store_locator_contact_form` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_locator_contact_form_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOL;

        $connection->executeStatement($sql);

        $helper = new ColumnHelper($connection);
        $helper->update('neti_store_locator_contact_form', 'label', 'DROP COLUMN `%s`');
        $helper->update('neti_store_locator_contact_form', 'value', 'DROP COLUMN `%s`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
