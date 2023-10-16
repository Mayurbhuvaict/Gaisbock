<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1614437579SalesChannel extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1614437579;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            ALTER TABLE `hueb_seo_faq_group`
            ADD COLUMN `sales_channel_id` BINARY(16) NULL
                AFTER `name`;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
