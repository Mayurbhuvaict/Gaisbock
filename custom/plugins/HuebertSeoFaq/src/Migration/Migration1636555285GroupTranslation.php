<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1636555285GroupTranslation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1636555285;
    }

    public function update(Connection $connection): void
    {
        $translationDatabase = '
            CREATE TABLE IF NOT EXISTS `hueb_seo_faq_group_translation` (
              `name` LONGTEXT NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              `hueb_seo_faq_group_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              PRIMARY KEY (`hueb_seo_faq_group_id`, `language_id`),
              CONSTRAINT `fk.hueb_seo_faq_group_translation.hueb_seo_faq_group_id` FOREIGN KEY (`hueb_seo_faq_group_id`) REFERENCES `hueb_seo_faq_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.hueb_seo_faq_group_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
        ';

        $editExistingTable = '
            ALTER TABLE
            `hueb_seo_faq_group` CHANGE name name_old VARCHAR(255) NULL;
        ';

        $connection->executeStatement($translationDatabase);
        $connection->executeStatement($editExistingTable);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
