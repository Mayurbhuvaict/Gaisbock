<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1585055462 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_585_055_462;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);

        $helper->add('neti_store_locator', 'detail_content_type', 'ADD COLUMN `detail_content_type` INT(11)');
        $helper->add('neti_store_locator', 'cms_page_id', 'ADD COLUMN `cms_page_id` BINARY(16)');

        $helper->add('neti_store_locator_translation', 'detail_title', 'ADD COLUMN `detail_title` VARCHAR(255)');
        $helper->add('neti_store_locator_translation', 'detail_description', 'ADD COLUMN `detail_description` TEXT');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
