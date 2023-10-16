<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1685413138AddStorageTypeToPopupTables extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1685413138;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
            ALTER TABLE `neno_marketing_essentials_newsletter_popup`
                ADD COLUMN `storage_type` VARCHAR(255) NULL AFTER `dev_mode`;
            ALTER TABLE `neno_marketing_essentials_register_popup`
                ADD COLUMN `storage_type` VARCHAR(255) NULL AFTER `dev_mode`;
        SQL;

        $connection->executeStatement($query);

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
