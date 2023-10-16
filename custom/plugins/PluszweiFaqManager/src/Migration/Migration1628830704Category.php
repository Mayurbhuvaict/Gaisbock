<?php declare(strict_types=1);

namespace Pluszwei\FaqManager\Migration;

use Doctrine\DBAL\Connection;
use Pluszwei\FaqManager\Core\Content\Category\CategoryDefinition;
use Pluszwei\FaqManager\Core\Content\Category\CategorySeoUrlRoute;
use Pluszwei\FaqManager\Core\Content\Category\Translation\CategoryTranslationDefinition;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Migration\Traits\ImportTranslationsTrait;
use Shopware\Core\Migration\Traits\Translations;

class Migration1628830704Category extends MigrationStep
{
    use ImportTranslationsTrait;

    public function getCreationTimestamp(): int
    {
        return 1628830704;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_category` (
                `id` BINARY(16) NOT NULL,
                `version_id` BINARY(16) NOT NULL,
                `active` TINYINT DEFAULT 0,
                `parent_id` BINARY(16) NULL,
                `navigation_id` BINARY(16) NULL,
                `parent_version_id` BINARY(16) NULL,
                `after_category_id` BINARY(16) NULL,
                `level` INT(11) NULL,
                `path` LONGTEXT NULL,
                `child_count` INT(11) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`, `version_id`),
                KEY `fk.pluszwei_faq_category.parent_id` (`parent_id`, `parent_version_id`),
                CONSTRAINT `fk.pluszwei_faq_category.parent_id` FOREIGN KEY (`parent_id`, `parent_version_id`) REFERENCES `pluszwei_faq_category` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `pluszwei_faq_category_translation` (
                `pluszwei_faq_category_id` BINARY(16) NOT NULL,
                `pluszwei_faq_category_version_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                `name` VARCHAR(255) NULL,
                `breadcrumb` json NULL,
                `description` LONGTEXT COLLATE utf8mb4_unicode_ci NULL,
                `meta_title` VARCHAR(255) NULL,
                `meta_description` VARCHAR(255) NULL,
                `keywords` varchar(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`pluszwei_faq_category_id`,`language_id`, `pluszwei_faq_category_version_id`),
                CONSTRAINT `fk.pluszwei_faq_category_translation.pluszwei_faq_category_id` FOREIGN KEY (`pluszwei_faq_category_id`, `pluszwei_faq_category_version_id`) 
                    REFERENCES `pluszwei_faq_category` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.pluszwei_faq_category_translation.language_id` FOREIGN KEY (`language_id`) 
                    REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            INSERT INTO `seo_url_template` (`id`, `sales_channel_id`, `route_name`, `entity_name`, `template`, `is_valid`, `custom_fields`, `created_at`, `updated_at`)
            VALUES (:id, NULL, :routeName, :entityName, :template, 1, NULL, :createdAt, NULL);
        ', [
            'id' => Uuid::randomBytes(),
            'routeName' => CategorySeoUrlRoute::ROUTE_NAME,
            'entityName' => 'pluszwei_faq_category',
            'template' => CategorySeoUrlRoute::DEFAULT_TEMPLATE,
            'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $this->defaultCategorySeeder($connection);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function defaultCategorySeeder(Connection $connection)
    {
        $version = Uuid::fromHexToBytes(Defaults::LIVE_VERSION);
        $categoryId = $this->createRootCategory($connection);
        $translations = new Translations(
            [
                'pluszwei_faq_category_id' => $categoryId,
                'name' => 'Default',
                'pluszwei_faq_category_version_id' => $version
            ],
            [
                'pluszwei_faq_category_id' => $categoryId,
                'name' => 'Default',
                'pluszwei_faq_category_version_id' => $version
            ]
        );
        $this->importTranslation(CategoryTranslationDefinition::ENTITY_NAME, $translations, $connection);
    }

    private function createRootCategory(Connection $connection): string
    {
        $id = Uuid::randomBytes();
        $connection->insert(CategoryDefinition::ENTITY_NAME,
            [
                'id' => $id,
                'version_id' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
                'level' => 1,
                'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)
            ]
        );

        return $id;
    }
}
