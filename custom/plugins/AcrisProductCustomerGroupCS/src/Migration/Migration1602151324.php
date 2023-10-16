<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1602151324 extends MigrationStep
{
    use InheritanceUpdaterTrait;

    public function getCreationTimestamp(): int
    {
        return 1602151324;
    }

    public function update(Connection $connection): void
    {



        $query = <<<SQL
            CREATE TABLE IF NOT EXISTS `acris_product_customer_group` (
                `product_id` BINARY(16) NOT NULL,
                `product_version_id` BINARY(16) NOT NULL,
                `customer_group_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`product_id`,`customer_group_id`),
                KEY `fk.acris_product_customer_group.product_id` (`product_id`,`product_version_id`),
                KEY `fk.acris_product_customer_group.customer_group_id` (`customer_group_id`),
                CONSTRAINT `fk.acris_product_customer_group.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.acris_product_customer_group.customer_group_id` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($query);

        $this->updateInheritance($connection, 'product', 'customerGroup');
        $this->updateInheritance($connection, 'customer_group', 'product');

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
