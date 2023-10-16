<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1581489743ShoppingExperience extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1581489743;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
        CREATE TABLE IF NOT EXISTS `s_plugin_hatslogic_shopping_experiences` (
          `id` BINARY(16) NOT NULL,
          `sales_channel_id` BINARY(16) NOT NULL,
          `customer_id` BINARY(16) NULL,
          `points` SMALLINT(5) NOT NULL,
          `comment` TEXT NULL,
          `created_at` DATETIME(3) NOT NULL,
          `updated_at` DATETIME(3) NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
