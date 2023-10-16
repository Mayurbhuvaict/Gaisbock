<?php declare(strict_types=1);

namespace Laenen\Giftcard\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1667946821CreateGiftcardTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1667946821;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(<<<SQL
CREATE TABLE `lae_giftcard` (
    `id` BINARY(16) NOT NULL,
    `name` VARCHAR(255) NULL,
    `description` LONGTEXT NULL,
    `code` VARCHAR(255) NOT NULL,
    `balance` DOUBLE NULL,
    `initial_amount` DOUBLE NULL,
    `currency_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NULL,
    `origin_order_id` BINARY(16) NULL,
    `origin_order_version_id` BINARY(16) NULL,
    `sales_channel_id` BINARY(16) NULL,
    `custom_fields` JSON NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `json.lae_giftcard.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
    KEY `fk.lae_giftcard.currency_id` (`currency_id`),
    CONSTRAINT `fk.lae_giftcard.currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE UNIQUE INDEX `idx.lae_giftcard.code`
ON lae_giftcard (`code`);
SQL);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
