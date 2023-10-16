<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @psalm-type Store = array{
 *      cms_page_id: string,
 *      id: string
 *  }
 */
class Migration1695646927CmsRevamp extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_695_646_927;
    }

    /**
     * @throws Exception
     */
    public function update(Connection $connection): void
    {
        $this->createTable($connection);
        $this->migrateOldCmsPages($connection);
        $this->dropColumn($connection);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    /**
     * @throws Exception
     */
    private function createTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_store_cms` (
    `id` BINARY(16) NOT NULL,
    `position` INT(11) NOT NULL,
    `store_id` BINARY(16) NOT NULL,
    `cms_page_id` BINARY(16) NULL,
    `cms_page_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk.neti_store_cms.store_id` (`store_id`),
    KEY `fk.neti_store_cms.cms_page_id` (`cms_page_id`,`cms_page_version_id`),
    CONSTRAINT `fk.neti_store_cms.store_id` FOREIGN KEY (`store_id`) REFERENCES `neti_store_locator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_cms.cms_page_id` FOREIGN KEY (`cms_page_id`,`cms_page_version_id`) REFERENCES `cms_page` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    /**
     * @throws Exception
     */
    private function migrateOldCmsPages(Connection $connection): void
    {
        $stores = $this->getStoreData($connection);

        $versionId = Uuid::fromHexToBytes(Defaults::LIVE_VERSION);
        $storeSql  = <<<SQL
INSERT INTO `neti_store_cms` (`id`, `position`, `store_id`, `cms_page_id`, `cms_page_version_id`)
VALUES (?, ?, ?, ?, ?);
SQL;

        /** @psalm-var Store $store */
        foreach ($stores as $store) {
            $id = Uuid::randomBytes();
            $connection->executeStatement($storeSql, [$id, 0, $store['id'], $store['cms_page_id'], $versionId]);
        }
    }

    /**
     * @throws Exception
     */
    private function getStoreData(Connection $connection): array
    {
        $sql = <<<SQL
SELECT `id`, `cms_page_id` FROM `neti_store_locator` WHERE id IS NOT NULL;
SQL;

        return $connection->fetchAllAssociative($sql);
    }

    /**
     * @throws Exception
     */
    private function dropColumn(Connection $connection): void
    {
        $sql = <<<SQL
ALTER TABLE `neti_store_locator`
  DROP COLUMN `cms_page_id`,
  DROP COLUMN `cms_page_version_id`;
SQL;

        $connection->executeStatement($sql);
    }
}
