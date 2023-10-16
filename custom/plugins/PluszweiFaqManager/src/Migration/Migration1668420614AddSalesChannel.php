<?php declare(strict_types=1);

namespace Pluszwei\FaqManager\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1668420614AddSalesChannel extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1668420614;
    }

    public function update(Connection $connection): void
    {
        $this->addSalesChannel($connection);

        $this->setDefaultSalesChannelToArticle($connection);

        $this->setDefaultSalesChannelToCategory($connection);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function addSalesChannel(Connection $connection)
    {
        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_article_sales_channel` (
                `pluszwei_faq_article_id` BINARY(16) NOT NULL,
                `sales_channel_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`pluszwei_faq_article_id`, `sales_channel_id`),
                CONSTRAINT `fk.pluszwei_faq_article_sales_channel.pluszwei_faq_article_id` FOREIGN KEY (`pluszwei_faq_article_id`) REFERENCES `pluszwei_faq_article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.pluszwei_faq_article_sales_channel.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_category_sales_channel` (
                `pluszwei_faq_category_id` BINARY(16) NOT NULL,
                `pluszwei_faq_category_version_id` BINARY(16) NOT NULL,
                `sales_channel_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`pluszwei_faq_category_id`, `pluszwei_faq_category_version_id`, `sales_channel_id`),
                CONSTRAINT `fk.pluszwei_faq_category_sales_channel.pluszwei_faq_category_id` FOREIGN KEY (`pluszwei_faq_category_id`, `pluszwei_faq_category_version_id`) REFERENCES `pluszwei_faq_category` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.pluszwei_faq_category_sales_channel.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    private function setDefaultSalesChannelToArticle(Connection $connection)
    {
        $articles = $connection->fetchFirstColumn(
            'SELECT LOWER(HEX(id)) FROM pluszwei_faq_article'
        );

        if (count($articles) === 0) {
            return;
        }

        $salesChannels = $connection->fetchFirstColumn(
            'SELECT LOWER(HEX(id)) FROM sales_channel'
        );

        $values = [];
        foreach ($articles as $articleId) {
            foreach ($salesChannels as $salesChannelId) {
                $values[] = "(UNHEX('$articleId'), UNHEX('$salesChannelId'))";
            }
        }

        $valuesSQL = implode(',', $values);

        $sql = "INSERT INTO pluszwei_faq_article_sales_channel(pluszwei_faq_article_id, sales_channel_id) VALUES $valuesSQL;";

        $connection->executeStatement($sql);
    }

    private function setDefaultSalesChannelToCategory(Connection $connection)
    {
        $categories = $connection->fetchAllAssociative(
            'SELECT LOWER(HEX(id)) as id, LOWER(HEX(version_id)) as version_id FROM pluszwei_faq_category'
        );

        if (count($categories) === 0) {
            return;
        }

        $salesChannels = $connection->fetchFirstColumn(
            'SELECT LOWER(HEX(id)) FROM sales_channel'
        );

        $values = [];
        foreach ($categories as $category) {
            foreach ($salesChannels as $salesChannelId) {
                $values[] = "(UNHEX('{$category['id']}'), UNHEX('{$category['version_id']}'), UNHEX('$salesChannelId'))";
            }
        }

        $valuesSQL = implode(',', $values);

        $sql = "INSERT INTO pluszwei_faq_category_sales_channel(`pluszwei_faq_category_id`, `pluszwei_faq_category_version_id`, `sales_channel_id`) VALUES $valuesSQL;";

        $connection->executeStatement($sql);
    }
}
