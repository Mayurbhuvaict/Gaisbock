<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1584096712 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_584_096_712;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'seo_title', 'ADD COLUMN `seo_title` VARCHAR(255) AFTER `detail_page_enabled`');
        $helper->add('neti_store_locator', 'seo_description', 'ADD COLUMN `seo_description` TEXT AFTER `seo_url`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
