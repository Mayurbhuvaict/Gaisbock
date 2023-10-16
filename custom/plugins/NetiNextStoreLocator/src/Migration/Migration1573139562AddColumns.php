<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573139562AddColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_139_562;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'active', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'contact_form_enabled', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'hidden', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'notification_email', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'show_always', 'ADD COLUMN `%s` VARCHAR(32) DEFAULT "no"');
        $helper->add('neti_store_locator', 'zoom', 'ADD COLUMN `%s` INT(11) DEFAULT 15');
        $helper->add('neti_store_locator', 'exclude_from_sync', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'google_place_id', 'ADD COLUMN `%s` VARCHAR(255)');
        $helper->add('neti_store_locator', 'featured', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'radius', 'ADD COLUMN `%s` INT(11)');
        $helper->add('neti_store_locator', 'detail_page_enabled', 'ADD COLUMN `%s` TINYINT(2)');
        $helper->add('neti_store_locator', 'seo_url', 'ADD COLUMN `%s` VARCHAR(255)');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
