<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1585054485 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_585_054_485;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);

        $helper->update('neti_store_locator', 'seo_title', 'DROP COLUMN `seo_title`');
        $helper->update('neti_store_locator', 'seo_url', 'DROP COLUMN `seo_url`');
        $helper->update('neti_store_locator', 'seo_description', 'DROP COLUMN `seo_description`');

        $helper->add('neti_store_locator_translation', 'seo_title', 'ADD COLUMN `seo_title` VARCHAR(255)');
        $helper->add('neti_store_locator_translation', 'seo_url', 'ADD COLUMN `seo_url` VARCHAR(255)');
        $helper->add('neti_store_locator_translation', 'seo_description', 'ADD COLUMN `seo_description` TEXT');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
