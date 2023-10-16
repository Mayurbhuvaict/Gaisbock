<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1668615359AddOriginProductColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1668615359;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            <<<SQL
ALTER TABLE `lae_giftcard`
ADD COLUMN `origin_product_id` BINARY(16) NULL,
ADD COLUMN `origin_product_version_id` BINARY(16) NULL;
SQL
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
