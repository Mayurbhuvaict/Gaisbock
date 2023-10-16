<?php

namespace Neno\MarketingEssentials\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1665413137AddConversionBarTables extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_665_413_137;
    }

    final const CONVBAR_CONFIG_KEYS = [
        "conversionBarActive",
        "conversionBarSliderMaxWidth",
        "conversionBarBackgroundColor",
        "conversionBarTextColor",
        "conversionBarLinkColor",
        "conversionBarText1",
        "conversionBarText1Clickable",
        "conversionBarText1ClickableUrl",
        "conversionBarText1PrimaryAction",
        "conversionBarText1PrimaryActionText",
        "conversionBarText1PrimaryActionUrl",
        "conversionBarText1Icon",
        "conversionBarText2",
        "conversionBarText2Clickable",
        "conversionBarText2ClickableUrl",
        "conversionBarText2PrimaryAction",
        "conversionBarText2PrimaryActionText",
        "conversionBarText2PrimaryActionUrl",
        "conversionBarText2Icon",
        "conversionBarText3",
        "conversionBarText3Clickable",
        "conversionBarText3ClickableUrl",
        "conversionBarText3PrimaryAction",
        "conversionBarText3PrimaryActionText",
        "conversionBarText3PrimaryActionUrl",
        "conversionBarText3Icon",
    ];

    private function valueOrNull(array $values, string $key) {
        if (array_key_exists($key, $values)) {
            return $values[$key];
        }

        return null;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_conversion_bar` (
                `id` BINARY(16) NOT NULL,
                `sales_channel_id` BINARY(16) NULL UNIQUE,
                `active` VARCHAR(255) NULL,
                `slider_max_width` INT(11) NULL,
                `background_color` VARCHAR(255) NULL,
                `text_color` VARCHAR(255) NULL,
                `link_color` VARCHAR(255) NULL,
                `text_01_clickable` VARCHAR(255) NULL,
                `text_01_primary_active` VARCHAR(255) NULL,
                `text_01_media_id` BINARY(16) NULL,
                `text_02_clickable` VARCHAR(255) NULL,
                `text_02_primary_active` VARCHAR(255) NULL,
                `text_02_media_id` BINARY(16) NULL,
                `text_03_clickable` VARCHAR(255) NULL,
                `text_03_primary_active` VARCHAR(255) NULL,
                `text_03_media_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `fk.neno_marketing_essentials_conversion_bar.text_01_media_id` FOREIGN KEY (`text_01_media_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.neno_marketing_essentials_conversion_bar.text_02_media_id` FOREIGN KEY (`text_02_media_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.neno_marketing_essentials_conversion_bar.text_03_media_id` FOREIGN KEY (`text_03_media_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.neno_marketing_essentials_conversion_bar.sales_channel_id` FOREIGN KEY (`sales_channel_id`)
                    REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_conversion_bar_translation` (
                `neno_marketing_essentials_conversion_bar_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                `text_01` VARCHAR(255),
                `text_01_url` VARCHAR(255),
                `text_01_primary` VARCHAR(255),
                `text_01_primary_url` VARCHAR(255),
                `text_02` VARCHAR(255),
                `text_02_url` VARCHAR(255),
                `text_02_primary` VARCHAR(255),
                `text_02_primary_url` VARCHAR(255),
                `text_03` VARCHAR(255),
                `text_03_url` VARCHAR(255),
                `text_03_primary` VARCHAR(255),
                `text_03_primary_url` VARCHAR(255),
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`neno_marketing_essentials_conversion_bar_id`, `language_id`),
                CONSTRAINT `fk.neno_me_conversion_bar_translation.slide_id` FOREIGN KEY (`neno_marketing_essentials_conversion_bar_id`)
                    REFERENCES `neno_marketing_essentials_conversion_bar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.neno_me_conversion_bar_translation.language_id` FOREIGN KEY (`language_id`)
                    REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        // migrate existing config data from system config to new tables

        $configRows = $connection->fetchAllAssociative(
        <<<END
            SELECT * FROM `system_config` WHERE `configuration_key` LIKE 'NenoMarketingEssentials.config.%';
        END);

        $configBySalesChannelId = array();
        foreach ($configRows as $row) {
            $salesChannelId = $row['sales_channel_id'];
            if ($salesChannelId !== null) {
                $salesChannelId = Uuid::fromBytesToHex($salesChannelId);
            }

            if (!array_key_exists($salesChannelId, $configBySalesChannelId)) {
                $configBySalesChannelId[$salesChannelId] = array();
            }
            $salesChannelData = &$configBySalesChannelId[$salesChannelId];

            $key = explode(".", (string) $row['configuration_key'])[2];
            $jsonValue = $row['configuration_value'];

            if (in_array($key, self::CONVBAR_CONFIG_KEYS)) {
                $salesChannelData[$key] = json_decode((string) $jsonValue, true)['_value'];
            }
        }

        foreach ($configBySalesChannelId as $hexSalesChannelId => $values) {
            $insertId = Uuid::randomBytes();
            $salesChannelId = $hexSalesChannelId
                ? Uuid::fromHexToBytes($hexSalesChannelId)
                : null;

            try {
                $connection->insert('neno_marketing_essentials_conversion_bar', [
                    'id'                        => $insertId,
                    'sales_channel_id'          => $salesChannelId,
                    'active'                    => $this->valueOrNull($values, 'conversionBarActive') ? 1 : 0,
                    'slider_max_width'          => $this->valueOrNull($values, 'conversionBarSliderMaxWidth'),
                    'background_color'          => $this->valueOrNull($values, 'conversionBarBackgroundColor'),
                    'text_color'                => $this->valueOrNull($values, 'conversionBarTextColor'),
                    'link_color'                => $this->valueOrNull($values, 'conversionBarLinkColor'),
                    'text_01_clickable'         => $this->valueOrNull($values, 'conversionBarText1Clickable') ? 1 : 0,
                    'text_01_primary_active'    => $this->valueOrNull($values, 'conversionBarText1PrimaryAction') ? 1 : 0,
                    'text_01_media_id'          => $this->valueOrNull($values, 'conversionBarText1Icon')
                                                      ? Uuid::fromHexToBytes($this->valueOrNull($values, 'conversionBarText1Icon'))
                                                      : null,
                    'text_02_clickable'         => $this->valueOrNull($values, 'conversionBarText2Clickable') ? 1 : 0,
                    'text_02_primary_active'    => $this->valueOrNull($values, 'conversionBarText2PrimaryAction') ? 1 : 0,
                    'text_02_media_id'          => $this->valueOrNull($values, 'conversionBarText2Icon')
                                                      ? Uuid::fromHexToBytes($this->valueOrNull($values, 'conversionBarText2Icon'))
                                                      : null,
                    'text_03_clickable'         => $this->valueOrNull($values, 'conversionBarText3Clickable') ? 1 : 0,
                    'text_03_primary_active'    => $this->valueOrNull($values, 'conversionBarText3PrimaryAction') ? 1 : 0,
                    'text_03_media_id'          => $this->valueOrNull($values, 'conversionBarText3Icon')
                                                      ? Uuid::fromHexToBytes($this->valueOrNull($values, 'conversionBarText3Icon'))
                                                      : null,
                    'created_at'                => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)
                ]);

                $connection->insert('neno_marketing_essentials_conversion_bar_translation', [
                    'neno_marketing_essentials_conversion_bar_id' => $insertId,
                    'language_id'           => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
                    'text_01'               => $this->valueOrNull($values, 'conversionBarText1'),
                    'text_01_url'           => $this->valueOrNull($values, 'conversionBarText1ClickableUrl'),
                    'text_01_primary'       => $this->valueOrNull($values, 'conversionBarText1PrimaryActionText'),
                    'text_01_primary_url'   => $this->valueOrNull($values, 'conversionBarText1PrimaryActionUrl'),
                    'text_02'               => $this->valueOrNull($values, 'conversionBarText2'),
                    'text_02_url'           => $this->valueOrNull($values, 'conversionBarText2ClickableUrl'),
                    'text_02_primary'       => $this->valueOrNull($values, 'conversionBarText2PrimaryActionText'),
                    'text_02_primary_url'   => $this->valueOrNull($values, 'conversionBarText2PrimaryActionUrl'),
                    'text_03'               => $this->valueOrNull($values, 'conversionBarText3'),
                    'text_03_url'           => $this->valueOrNull($values, 'conversionBarText3ClickableUrl'),
                    'text_03_primary'       => $this->valueOrNull($values, 'conversionBarText3PrimaryActionText'),
                    'text_03_primary_url'   => $this->valueOrNull($values, 'conversionBarText3PrimaryActionUrl'),
                    'created_at'            => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)
                ]);
            } catch (ConstraintViolationException) {}
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
