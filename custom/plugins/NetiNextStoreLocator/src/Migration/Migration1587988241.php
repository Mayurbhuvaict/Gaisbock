<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1587988241 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_587_988_241;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);

        $helper->update('neti_store_locator', 'detail_content_type', 'MODIFY `detail_content_type` VARCHAR(32)');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
