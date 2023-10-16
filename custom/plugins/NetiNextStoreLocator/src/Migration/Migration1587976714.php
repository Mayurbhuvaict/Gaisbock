<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1587976714 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_587_976_714;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'contact_form_detail', 'ADD COLUMN `%s` TINYINT(2)');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
