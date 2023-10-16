<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration1685536359CmsSlotInstagramPost extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1685536359;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `solid_ie_cms_slot_instagram_post` (
                `id` BINARY(16) NOT NULL,
                `user_id` VARCHAR(255) NULL,
                `username` VARCHAR(255) NULL,
                `post_id` VARCHAR(255) NULL,
                `caption` MEDIUMTEXT NULL,
                `media_type` VARCHAR(255) NULL,
                `media_url` TEXT NULL,
                `permalink` TEXT NULL,
                `timestamp` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`)
            )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
