<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use NetInventors\NetiNextStoreLocator\Components\Migration\ColumnHelper;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1636367162 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_636_367_162;
    }

    /**
     * @param Connection $connection
     *
     * @throws Exception
     */
    public function update(Connection $connection): void
    {
        $helper = new ColumnHelper($connection);
        $helper->add('neti_store_locator', 'cms_page_version_id', 'ADD COLUMN `cms_page_version_id` BINARY(16) NULL AFTER `cms_page_id`');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
