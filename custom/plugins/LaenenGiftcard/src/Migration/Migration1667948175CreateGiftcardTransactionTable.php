<?php declare(strict_types=1);

namespace Laenen\Giftcard\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1667948175CreateGiftcardTransactionTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1667948175;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(<<<SQL
CREATE TABLE `lae_giftcard_transaction` (
    `id` BINARY(16) NOT NULL,
    `giftcard_id` BINARY(16) NULL,
    `amount` DOUBLE NULL,
    `order_id` BINARY(16) NULL,
    `order_version_id` BINARY(16) NULL,
    `comment` LONGTEXT NULL,
    `custom_fields` JSON NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `json.lae_giftcard_transaction.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
    KEY `fk.lae_giftcard_transaction.giftcard_id` (`giftcard_id`),
    CONSTRAINT `fk.lae_giftcard_transaction.giftcard_id` FOREIGN KEY (`giftcard_id`) REFERENCES `lae_giftcard` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
