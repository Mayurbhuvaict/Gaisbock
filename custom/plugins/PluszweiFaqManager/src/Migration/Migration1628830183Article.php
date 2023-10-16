<?php declare(strict_types=1);

namespace Pluszwei\FaqManager\Migration;

use Doctrine\DBAL\Connection;
use Pluszwei\FaqManager\Core\Content\Article\ArticleSeoUrlRoute;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1628830183Article extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1628830183;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            '
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_article` (
            `id` BINARY(16) NOT NULL,
            `active` TINYINT DEFAULT 0,
            `featured` TINYINT DEFAULT 0,
            `media_id` BINARY(16) DEFAULT NULL,
            `category_id` BINARY(16) NULL,
            `section_id` BINARY(16) NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        '
        );

        $connection->executeStatement(
            '
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_article_translation` (
            `pluszwei_faq_article_id` BINARY(16) NOT NULL,
            `language_id` BINARY(16) NOT NULL,
            `title` VARCHAR(255) NULL,
            `teaser` VARCHAR(255) NULL,
            `url` VARCHAR(255) NULL,
            `meta_title` VARCHAR(255) NULL,
            `meta_description` VARCHAR(255) NULL,
            `keywords` varchar(255) NULL,
            `content` LONGTEXT COLLATE utf8mb4_unicode_ci NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`pluszwei_faq_article_id`, `language_id`),
            CONSTRAINT `fk.pluszwei_faq_article_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.pluszwei_faq_article_translation.pluszwei_faq_article_id` FOREIGN KEY (`pluszwei_faq_article_id`)
                REFERENCES `pluszwei_faq_article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        '
        );

            $connection->executeStatement('
                INSERT INTO `seo_url_template` (`id`, `sales_channel_id`, `route_name`, `entity_name`, `template`, `is_valid`, `custom_fields`, `created_at`, `updated_at`)
                VALUES (:id, NULL, :routeName, :entityName, :template, 1, NULL, :createdAt, NULL);
            ', [
                'id' => Uuid::randomBytes(),
                'routeName' => ArticleSeoUrlRoute::ROUTE_NAME,
                'entityName' => 'pluszwei_faq_article',
                'template' => ArticleSeoUrlRoute::DEFAULT_TEMPLATE,
                'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
