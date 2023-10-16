<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1587686400 extends MigrationStep
{
    use InheritanceUpdaterTrait;

    public function getCreationTimestamp(): int
    {
        return 1586326495;
    }

    public function update(Connection $connection): void
    {



        $query = <<<SQL
            CREATE TABLE IF NOT EXISTS `acris_category_customer_group` (
                `category_id` BINARY(16) NOT NULL,
                `customer_group_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`category_id`,`customer_group_id`),
                KEY `fk.acris_category_customer_group.category_id` (`category_id`),
                KEY `fk.acris_category_customer_group.customer_group_id` (`customer_group_id`),
                CONSTRAINT `fk.acris_category_customer_group.category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.acris_category_customer_group.customer_group_id` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeUpdate($query);

        $this->updateInheritance($connection, 'category', 'customerGroup');
        $this->updateInheritance($connection, 'customer_group', 'category');

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
