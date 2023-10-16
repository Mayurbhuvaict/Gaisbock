<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1607683101 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_607_683_101;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
SELECT `mapping` FROM `import_export_profile` WHERE `name` = "StoreLocator" AND `source_entity` = "neti_store_locator"
SQL;

        $json   = (string) $connection->executeQuery($sql)->fetchOne();
        /** @var array|null $result */
        $result = json_decode($json, true);

        if (null === $result) {
            throw new \Exception('Could not update the Import/Export profile for StoreLocator');
        }

        $newProfileFields = [
            [
                'key'       => 'tags',
                'mappedKey' => 'tags',
            ],
            [
                'key'       => 'translations.DEFAULT.seoDescription',
                'mappedKey' => 'seo_description',
            ],
            [
                'key'       => 'translations.DEFAULT.seoTitle',
                'mappedKey' => 'seo_title',
            ],
            [
                'key'       => 'translations.DEFAULT.seoUrl',
                'mappedKey' => 'seo_url',
            ],
            [
                'key'       => 'translations.DEFAULT.additionalInformation',
                'mappedKey' => 'additional_information',
            ],
            [
                'key'       => 'translations.DEFAULT.description',
                'mappedKey' => 'description',
            ],
            [
                'key'       => 'detailContentType',
                'mappedKey' => 'detail_content_type',
            ],
            [
                'key'       => 'translations.DEFAULT.detailDescription',
                'mappedKey' => 'detail_description',
            ],
            [
                'key'       => 'translations.DEFAULT.detailTitle',
                'mappedKey' => 'detail_title',
            ],
        ];

        foreach ($newProfileFields as $newProfileField) {
            $result[] = $newProfileField;
        }

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
