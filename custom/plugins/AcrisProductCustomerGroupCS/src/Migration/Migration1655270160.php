<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1655270160 extends MigrationStep
{
    use InheritanceUpdaterTrait;

    public function getCreationTimestamp(): int
    {
        return 1655270160;
    }

    public function update(Connection $connection): void
    {
        try {
            $connection->executeStatement('ALTER TABLE `product` DROP COLUMN `customerGroup`;');
            $connection->executeStatement('ALTER TABLE `customer_group` DROP COLUMN `product`;');
        } catch (\Throwable $e) {}

        $this->updateInheritance($connection, 'product', 'acrisBlockCustomerGroup');
        $this->updateInheritance($connection, 'customer_group', 'acrisBlockProduct');

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
