<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1605903707Core extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1605903707;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `hueb_seo_faq_group` (
              `id` BINARY(16) NOT NULL,
              `active` TINYINT(1) NULL,
              `name` VARCHAR(255) NULL,
              `created_at` DATETIME(3) NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `hueb_seo_faq_questions` (
              `id` BINARY(16) NOT NULL,
              `active` TINYINT(1) NULL,
              `name` VARCHAR(255) NOT NULL,
              `group` BINARY(16) NULL,
              `created_at` DATETIME(3) NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `fk.seo_faq_questions.group` FOREIGN KEY (`group`)
                REFERENCES `hueb_seo_faq_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `hueb_seo_faq_questions_translation` (
              `hueb_seo_faq_questions_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              `question` VARCHAR(255) NULL,
              `answer` VARCHAR(255) NULL,
              `meta_title` VARCHAR(255) NULL,
              `meta_description` VARCHAR(255) NULL,
              `keywords` VARCHAR(255) NULL,
              `created_at` DATETIME(3) NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`hueb_seo_faq_questions_id`, `language_id`),
              CONSTRAINT `fk.seo_faq_questions_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.seo_faq_questions_translation.seo_faq_questions_id` FOREIGN KEY (`hueb_seo_faq_questions_id`)
                REFERENCES `hueb_seo_faq_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
