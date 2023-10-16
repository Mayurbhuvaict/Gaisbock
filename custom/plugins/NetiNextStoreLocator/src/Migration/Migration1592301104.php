<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1592301104 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_592_301_104;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `neti_store_tag` (
    `store_id` BINARY(16) NOT NULL,
    `tag_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`store_id`,`tag_id`),
    KEY `fk.neti_store_tag.store_id` (`store_id`),
    KEY `fk.neti_store_tag.tag_id` (`tag_id`),
    CONSTRAINT `fk.neti_store_tag.store_id` FOREIGN KEY (`store_id`) REFERENCES `neti_store_locator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_tag.tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOL;

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
