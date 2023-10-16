<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1675415964AddHashForUnique extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1675415964;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
            ALTER TABLE `acris_customers_also_viewed` ADD COLUMN `row_hash` BINARY(16) NULL, ADD UNIQUE (row_hash);
SQL;

        $connection->executeUpdate($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
