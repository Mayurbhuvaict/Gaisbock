<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1573209317AddColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_573_209_317;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'label', 'ADD COLUMN `%s` VARCHAR(255) NOT NULL AFTER `id`');
        $helper->add('neti_store_locator', 'picture_media_id', 'ADD COLUMN `%s` BINARY(16)');
        $helper->add('neti_store_locator', 'icon_media_id', 'ADD COLUMN `%s` BINARY(16)');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
