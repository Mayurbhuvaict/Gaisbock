<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1611068135AddCustomFieldSetColumns extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_611_068_135;
    }

    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'custom_fields', 'ADD COLUMN `%s` JSON NULL');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
