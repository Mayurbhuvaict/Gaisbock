<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1648797644AdjustAdditionalInformation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_648_797_644;
    }

    public function update(Connection $connection): void
    {
        $query = '
            ALTER TABLE `neti_store_locator_translation`
            MODIFY COLUMN `additional_information` LONGTEXT NULL;
        ';

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
