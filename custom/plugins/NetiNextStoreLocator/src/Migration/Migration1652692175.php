<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1652692175 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_652_692_175;
    }

    public function update(Connection $connection): void
    {
        $sql = '
             ALTER TABLE `neti_store_locator_contact_form_translation`
               DROP FOREIGN KEY `fk.neti_contact_form_translation.neti_contact_form_id`,
               DROP FOREIGN KEY `fk.neti_store_locator_contact_form_translation.language_id`;

              ALTER TABLE neti_store_locator_contact_form_translation
                ADD CONSTRAINT `fk.neti_contact_form_translation.neti_contact_form_id`
                  FOREIGN KEY (`neti_store_locator_contact_form_id`) REFERENCES `neti_store_locator_contact_form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `fk.neti_store_locator_contact_form_translation.language_id` 
                  FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ';

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
