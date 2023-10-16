<?php declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1695805093 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1695805093;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $connection->executeQuery("
            CREATE TABLE IF NOT EXISTS `newsletter_popup` (
                `id` BINARY(16) NOT NULL,
                `dev_mode` TINYINT(1) NULL DEFAULT '0',
                `storage_type` VARCHAR(255) NULL,
                `is_global` TINYINT(1) NULL DEFAULT '0',
                `visible_settings` VARCHAR(255) NULL,
                `category_id` BINARY(16) NOT NULL,
                `product_id` BINARY(16) NULL,
                `popup_trigger` VARCHAR(255) NULL,
                `popup_time` INT(11) NULL,
                `popup_scroll` INT(11) NULL,
                `height_mobile` INT(11) NULL,
                `height_desktop` INT(11) NULL,
                `show_first_name` TINYINT(1) NULL DEFAULT '0',
                `show_last_name` TINYINT(1) NULL DEFAULT '0',
                `headline_font_family` VARCHAR(255) NULL,
                `headline_font_size_mobile` INT(11) NULL,
                `headline_line_height_mobile` INT(11) NULL,
                `headline_font_size_tablet` INT(11) NULL,
                `headline_line_height_tablet` INT(11) NULL,
                `headline_font_size_desktop` INT(11) NULL,
                `headline_line_height_desktop` INT(11) NULL,
                `subline_font_family` VARCHAR(255) NULL,
                `subline_font_size_mobile` INT(11) NULL,
                `subline_line_height_mobile` INT(11) NULL,
                `subline_font_size_tablet` INT(11) NULL,
                `subline_line_height_tablet` INT(11) NULL,
                `subline_font_size_desktop` INT(11) NULL,
                `subline_line_height_desktop` INT(11) NULL,
                `promotion_font_family` VARCHAR(255) NULL,
                `promotion_font_size_mobile` INT(11) NULL,
                `promotion_line_height_mobile` INT(11) NULL,
                `promotion_font_size_tablet` INT(11) NULL,
                `promotion_line_height_tablet` INT(11) NULL,
                `promotion_font_size_desktop` INT(11) NULL,
                `promotion_line_height_desktop` INT(11) NULL,
                `media_background_color` VARCHAR(255) NULL,
                `image_position` VARCHAR(255) NULL,
                `image_fit` VARCHAR(255) NULL,
                `image_alignment` VARCHAR(255) NULL,
                `image_mobile_settings` VARCHAR(255) NULL,
                `background_color` VARCHAR(255) NULL,
                `close_button_color` VARCHAR(255) NULL,
                `close_button_hover_color` VARCHAR(255) NULL,
                `promotion_color` VARCHAR(255) NULL,
                `mail_field_border_color` VARCHAR(255) NULL,
                `first_name_field_border_color` VARCHAR(255) NULL,
                `last_name_field_border_color` VARCHAR(255) NULL,
                `subscribe_button_background_color` VARCHAR(255) NULL,
                `subscribe_button_background_hover_color` VARCHAR(255) NULL,
                `subscribe_button_text_color` VARCHAR(255) NULL,
                `subscribe_button_text_hover_color` VARCHAR(255) NULL,
                `non_subscribe_text_color` VARCHAR(255) NULL,
                `non_subscribe_text_hover_color` VARCHAR(255) NULL,
                `popup_border_radius` INT(11) NULL,
                `mail_field_border_radius` INT(11) NULL,
                `first_name_field_border_radius` INT(11) NULL,
                `last_name_field_border_radius` INT(11) NULL,
                `subscribe_button_border_radius` INT(11) NULL,
                `content_alignment` VARCHAR(255) NULL,
                `promotion_active` TINYINT(1) NULL DEFAULT '0',
                `promotion_show_valid_until` TINYINT(1) NULL DEFAULT '0',
                `promotion_id` BINARY(16) NULL,
                `media_image_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.newsletter_popup.media_image_id` (`media_image_id`),
                CONSTRAINT `fk.newsletter_popup.media_image_id` FOREIGN KEY (`media_image_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $connection->executeQuery("
            CREATE TABLE IF NOT EXISTS `newsletter_popup_translation` (
                `name` VARCHAR(255) NOT NULL,
                `headline` LONGTEXT NULL,
                `subline` LONGTEXT NULL,
                `promotion_text_valid_until` VARCHAR(255) NULL,
                `first_name_field_placeholder` VARCHAR(255) NULL,
                `last_name_field_placeholder` VARCHAR(255) NULL,
                `mail_field_placeholder` VARCHAR(255) NULL,
                `text_subscribe_button` VARCHAR(255) NULL,
                `text_non_subscribe` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `newsletter_popup_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`newsletter_popup_id`,`language_id`),
                KEY `fk.newsletter_popup_translation.newsletter_popup_id` (`newsletter_popup_id`),
                KEY `fk.newsletter_popup_translation.language_id` (`language_id`),
                CONSTRAINT `fk.newsletter_popup_translation.newsletter_popup_id` FOREIGN KEY (`newsletter_popup_id`) REFERENCES `newsletter_popup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.newsletter_popup_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $connection->executeQuery("
            CREATE TABLE IF NOT EXISTS `reserved_individual_promotion_code` (
                `id` BINARY(16) NOT NULL,
                `promotion_id` BINARY(16) NOT NULL,
                `promotion_individual_code_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
