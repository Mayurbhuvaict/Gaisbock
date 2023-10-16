<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1644729219AddReservedIndividualCodesTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_644_729_219;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `neno_nme_reserved_individual_code` (
                `id` BINARY(16) NOT NULL,
                `promotion_id` BINARY(16) NOT NULL,
                `promotion_individual_code_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `fk.nme_reserved_individual_code.promotion_individual_code_id` FOREIGN KEY (`promotion_individual_code_id`)
                    REFERENCES `promotion_individual_code` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
