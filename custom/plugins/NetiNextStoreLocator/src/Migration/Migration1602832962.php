<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1602832962 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_602_832_962;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
SELECT `mapping` FROM `import_export_profile` WHERE `name` = "StoreLocator" AND `source_entity` = "neti_store_locator"
SQL;

        $json = (string) $connection->executeQuery($sql)->fetchOne();
        /** @var array|null $result */
        $result = json_decode($json, true);

        if (null === $result) {
            throw new \Exception('Could not update the Import/Export profile for StoreLocator');
        }

        $result[] = [
            'key'       => 'country.iso',
            'mappedKey' => 'country_iso',
        ];

        $result[] = [
            'key'       => 'countryState.shortCode',
            'mappedKey' => 'country_state_short_code',
        ];

        $result[] = [
            'key'       => 'countryStateId',
            'mappedKey' => 'country_state_id',
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
