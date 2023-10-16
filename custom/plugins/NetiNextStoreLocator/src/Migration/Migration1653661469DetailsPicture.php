<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1653661469DetailsPicture extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_653_661_469;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'details_picture_media_id', 'ADD COLUMN `%s` BINARY(16)');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
