<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1678986779NewsletterAtRegisterSubscription extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1678986779;
    }

    public function update(Connection $connection): void
    {
        $this->createSubscriptionTable($connection);
        $this->migratePendingSubscriptions($connection);
    }

    public function updateDestructive(Connection $connection): void
    {
    }

    private function createSubscriptionTable(Connection $connection): void
    {
        $sql = <<<'SQL'
            CREATE TABLE `swkweb_newsletter_at_register_subscription` (
                `customer_id` binary(16) NOT NULL,
                PRIMARY KEY (`customer_id`),
                CONSTRAINT `fk.swkweb_newsletter_at_register_subscription.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            SQL;

        $connection->executeStatement($sql);
    }

    private function migratePendingSubscriptions(Connection $connection): void
    {
        if (!$this->columnExists($connection, 'customer', 'newsletter')) {
            return;
        }

        $sql = <<<'SQL'
            INSERT INTO
                `swkweb_newsletter_at_register_subscription` (
                    `customer_id`
                )
                SELECT
                    `id`
                FROM
                    `customer`
                WHERE
                    `newsletter` = 1
                    AND `double_opt_in_registration` = 1
                    AND `double_opt_in_confirm_date` IS NULL
                    AND `active` = 0;
            SQL;

        $connection->executeStatement($sql);
    }
}
