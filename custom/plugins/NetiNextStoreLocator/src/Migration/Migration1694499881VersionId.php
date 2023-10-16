<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1694499881VersionId extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_694_499_881;
    }

    /**
     * @throws Exception
     */
    public function update(Connection $connection): void
    {
        $sql = '
            UPDATE neti_store_locator
            SET cms_page_version_id = ?
            WHERE cms_page_id IS NOT NULL
              AND cms_page_version_id IS NULL
        ';

        $connection->executeStatement($sql, [
            Uuid::fromHexToBytes(Defaults::LIVE_VERSION)
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
