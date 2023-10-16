<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1669139187CreateDefaultMediaFolder extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1669139187;
    }

    public function update(Connection $connection): void
    {
        $this->createDefaultMediaFolders($connection);
    }

    private function createDefaultMediaFolders(Connection $connection): void
    {
        $defaultFolderId = Uuid::randomBytes();
        $connection->insert('media_default_folder', [
            'id' => $defaultFolderId,
            'association_fields' => '[]',
            'entity' => 'lae_giftcard',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->transactional(function (Connection $connection) use ($defaultFolderId): void {
            $configurationId = Uuid::randomBytes();
            $folderId = Uuid::randomBytes();
            $folderName = 'Giftcard Media';

            $connection->insert('media_folder_configuration', [
                'id' => $configurationId,
                'thumbnail_quality' => 80,
                'create_thumbnails' => 0,
                'private' => 0,
                'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);

            $connection->insert('media_folder', [
                'id' => $folderId,
                'name' => $folderName,
                'default_folder_id' => $defaultFolderId,
                'media_folder_configuration_id' => $configurationId,
                'use_parent_configuration' => 0,
                'child_count' => 0,
                'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        });
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
