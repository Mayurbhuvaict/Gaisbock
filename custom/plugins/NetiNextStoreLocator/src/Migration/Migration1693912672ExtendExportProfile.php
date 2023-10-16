<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @psalm-type ImportField = array{
 *     key: string,
 *     mappedKey: string
 * }
 */
class Migration1693912672ExtendExportProfile extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_693_912_672;
    }

    /**
     * @throws Exception
     */
    public function update(Connection $connection): void
    {
        $sql = <<<SQL
SELECT `mapping` FROM `import_export_profile` WHERE `name` = "StoreLocator" AND `source_entity` = "neti_store_locator"
SQL;

        $json   = (string) $connection->executeQuery($sql)->fetchOne();
        /** @var array|null $result */
        $result = json_decode($json, true);

        if (null === $result) {
            return;
        }

        /** @psalm-var ImportField $importField */
        foreach ($result as $importField) {
            if ($importField['key'] === 'externalId' || $importField['mappedKey'] === 'external_id') {
                return;
            }
        }

        $result[] = [
            'key'       => 'externalId',
            'mappedKey' => 'external_id',
        ];

        $result = json_encode($result);
        $sql    = <<<SQL
UPDATE `import_export_profile` SET `mapping` = ? WHERE `name` = "StoreLocator"
SQL;

        $connection->executeStatement($sql, [ $result ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
