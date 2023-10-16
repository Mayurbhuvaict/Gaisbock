<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1642147726BusinessHours extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_642_147_726;
    }

    public function update(Connection $connection): void
    {
        $this->createBusinessHourTable($connection);
        $this->createBusinessHourTranslationTable($connection);
        $this->createBusinessWeekdayTable($connection);
        $this->createBusinessWeekdayTranslationTable($connection);
        $this->createStoreBusinessHourTable($connection);
        $this->fillWeekdaysTable($connection);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    public function createBusinessHourTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_business_hour` (
    `id` BINARY(16) NOT NULL,
    `start` VARCHAR(255) NOT NULL,
    `end` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function createBusinessHourTranslationTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_business_hour_translation` (
    `description` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `neti_business_hour_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`neti_business_hour_id`,`language_id`),
    KEY `fk.neti_business_hour_translation.neti_business_hour_id` (`neti_business_hour_id`),
    KEY `fk.neti_business_hour_translation.language_id` (`language_id`),
    CONSTRAINT `fk.neti_business_hour_translation.neti_business_hour_id` FOREIGN KEY (`neti_business_hour_id`) REFERENCES `neti_business_hour` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_business_hour_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function createBusinessWeekdayTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_business_weekday` (
    `id` BINARY(16) NOT NULL,
    `number` INT(11) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function createBusinessWeekdayTranslationTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_business_weekday_translation` (
    `name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `neti_business_weekday_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`neti_business_weekday_id`,`language_id`),
    KEY `fk.neti_business_weekday_translation.neti_business_weekday_id` (`neti_business_weekday_id`),
    KEY `fk.neti_business_weekday_translation.language_id` (`language_id`),
    CONSTRAINT `fk.neti_business_weekday_translation.neti_business_weekday_id` FOREIGN KEY (`neti_business_weekday_id`) REFERENCES `neti_business_weekday` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_business_weekday_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function createStoreBusinessHourTable(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `neti_store_business_hour` (
    `id` BINARY(16) NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT '0',
    `annual` TINYINT(1) NOT NULL DEFAULT '0',
    `special_date` DATE NULL,
    `store_id` BINARY(16) NOT NULL,
    `business_hour_id` BINARY(16) NULL,
    `business_weekday_id` BINARY(16) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.neti_store_business_hour.store_id` (`store_id`),
    KEY `fk.neti_store_business_hour.business_hour_id` (`business_hour_id`),
    KEY `fk.neti_store_business_hour.business_weekday_id` (`business_weekday_id`),
    CONSTRAINT `fk.neti_store_business_hour.store_id` FOREIGN KEY (`store_id`) REFERENCES `neti_store_locator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_business_hour.business_hour_id` FOREIGN KEY (`business_hour_id`) REFERENCES `neti_business_hour` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.neti_store_business_hour.business_weekday_id` FOREIGN KEY (`business_weekday_id`) REFERENCES `neti_business_weekday` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function fillWeekdaysTable(Connection $connection): void
    {
        $id = (string) $connection->executeQuery('SELECT id FROM neti_business_weekday')->fetchOne();

        if (!empty($id)) {
            return;
        }

        // Get all languages (de-DE, en-GB and probably the Defaults::SYSTEM_LANGUAGE)
        $languages = $this->getLanguages($connection);
        $weekdays  = $this->getWeekdays();

        $this->createWeekdayFields($connection, $weekdays);

        /**
         * Create translations for de-DE, en-GB and the default language
         * If the given language is not found in our store we always refer to en-GB
         */
        /** @var array $language */
        foreach ($languages as $language) {
            $locale = (string) $language['code'];

            $this->createWeekdayTranslations(
                $connection,
                $weekdays,
                $locale,
                (string) $language['id']
            );
        }
    }

    private function getLanguages(Connection $connection): array
    {
        // en-DE, de-DE, and language-system
        $sql = <<<SQL
SELECT l.id, locale.code
FROM language l
LEFT JOIN locale ON locale.id = l.locale_id
WHERE locale.code IN ("de-DE", "en-GB")
  OR HEX(l.id) = :languageId;
SQL;

        return $connection->fetchAllAssociative(
            $sql,
            [
                'languageId' => Defaults::LANGUAGE_SYSTEM,
            ]
        );
    }

    private function getWeekdays(): array
    {
        return [
            [
                'id'     => Uuid::randomBytes(),
                'number' => 1,
                'name'   => [
                    'de-DE' => 'Montag',
                    'en-GB' => 'Monday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 2,
                'name'   => [
                    'de-DE' => 'Dienstag',
                    'en-GB' => 'Tuesday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 3,
                'name'   => [
                    'de-DE' => 'Mittwoch',
                    'en-GB' => 'Wednesday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 4,
                'name'   => [
                    'de-DE' => 'Donnerstag',
                    'en-GB' => 'Thursday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 5,
                'name'   => [
                    'de-DE' => 'Freitag',
                    'en-GB' => 'Friday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 6,
                'name'   => [
                    'de-DE' => 'Samstag',
                    'en-GB' => 'Saturday',
                ],
            ],
            [
                'id'     => Uuid::randomBytes(),
                'number' => 7,
                'name'   => [
                    'de-DE' => 'Sonntag',
                    'en-GB' => 'Sunday',
                ],
            ],
        ];
    }

    private function createWeekdayFields(Connection $connection, array $weekdays): void
    {
        $sql = <<<SQL
INSERT INTO neti_business_weekday
  (id, number, created_at, updated_at)
VALUES
SQL;
        $params = [];

        /** @var array<array-key, array<array-key, string>> $weekday */
        foreach ($weekdays as $key => $weekday) {
            $sql .= '(:id_' . $key . ', :number_' . $key . ', NOW(), NULL),';
            $params['id_' . $key]     = $weekday['id'];
            $params['number_' . $key] = $weekday['number'];
        }

        $connection->executeStatement(
            \rtrim($sql, ','),
            $params
        );
    }

    private function createWeekdayTranslations(
        Connection $connection,
        array $weekdays,
        string $locale,
        string $languageId
    ): void {
        $sql = <<<SQL
INSERT INTO neti_business_weekday_translation
  (name, created_at, updated_at, neti_business_weekday_id, language_id)
VALUES
SQL;

        $params = ['languageId' => $languageId];

        /** @var array<array-key, array<array-key, string>> $weekday */
        foreach ($weekdays as $key => $weekday) {
            $sql .= '(:name_' . $key . ', NOW(), NULL, :fieldId_' . $key . ', :languageId),';
            $params['name_' . $key]    = $weekday['name'][$locale] ?? $weekday['name']['en-GB'];
            $params['fieldId_' . $key] = $weekday['id'];
        }

        $connection->executeStatement(
            \rtrim($sql, ','),
            $params
        );
    }
}
