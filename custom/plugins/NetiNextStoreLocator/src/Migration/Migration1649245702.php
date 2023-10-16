<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1649245702 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_649_245_702;
    }

    public function update(Connection $connection): void
    {
        $folderId             = Uuid::randomBytes();
        $defaultFolderId      = Uuid::randomBytes();
        $mediaConfigurationId = Uuid::randomBytes();

        $sql = '
            INSERT INTO media_default_folder
              (id, association_fields, entity, created_at)
            VALUES (?, ?, ?, NOW())
        ';

        $connection->executeStatement(
            $sql,
            [
                $defaultFolderId,
                json_encode([]),
                StoreDefinition::ENTITY_NAME,
            ]
        );

        $sql = '
            INSERT INTO media_folder_configuration
              (id, created_at)
            VALUES (?, NOW())
        ';

        $connection->executeStatement(
            $sql,
            [
                $mediaConfigurationId,
            ]
        );

        $sql = '
            INSERT INTO media_folder
              (id, default_folder_id, name, media_folder_configuration_id, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ';

        $connection->executeStatement(
            $sql,
            [
                $folderId,
                $defaultFolderId,
                'StoreLocator',
                $mediaConfigurationId,
            ]
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
