<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1610539344NenoMarketingEssentials extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_610_539_344;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_newsletter_popup` (
                `id` BINARY(16) NOT NULL,
                `dev_mode` VARCHAR(255) NULL,
                `is_global` VARCHAR(255) NULL,
                `visible_settings` VARCHAR(255) NULL,
                `category_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `popup_trigger` VARCHAR(255) NULL,
                `popup_time` INT(11) NULL,
                `popup_scroll` INT(11) NULL,
                `height_mobile` INT(11) NULL,
                `height_desktop` INT(11) NULL,
                `show_first_name` VARCHAR(255) NULL,
                `show_last_name` VARCHAR(255) NULL,
                `media_background_color` VARCHAR(255) NULL,
                `image_position` VARCHAR(255) NULL,
                `image_fit` VARCHAR(255) NULL,
                `image_alignment` VARCHAR(255) NULL,
                `image_mobile_settings` VARCHAR(255) NULL,
                `media_image_id` BINARY(16) NULL,
                `background_color` VARCHAR(255) NULL,
                `close_button_color` VARCHAR(255) NULL,
                `close_button_hover_color` VARCHAR(255) NULL,
                `promotion_color` VARCHAR(255) NULL,

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

                `promotion_active` VARCHAR(255) NULL,
                `promotion_show_valid_until` VARCHAR(255) NULL,
                `promotion_id` BINARY(16) NULL,

                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `fk.marketing_essentials_newsletter_popup.promotion_id` FOREIGN KEY (`promotion_id`)
                    REFERENCES `promotion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.marketing_essentials_newsletter_popup.media_image_id` FOREIGN KEY (`media_image_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_newsletter_popup_translation` (
              `neno_marketing_essentials_newsletter_popup_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              `name` VARCHAR(255),
              `headline` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
              `subline` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
              `promotion_text_valid_until` VARCHAR(255) NULL,
              `first_name_field_placeholder` VARCHAR(255) NULL,
              `last_name_field_placeholder` VARCHAR(255) NULL,
              `mail_field_placeholder` VARCHAR(255) NULL,
              `text_subscribe_button` VARCHAR(255) NULL,
              `text_non_subscribe` VARCHAR(255) NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`neno_marketing_essentials_newsletter_popup_id`, `language_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_register_popup` (
                `id` BINARY(16) NOT NULL,
                `dev_mode` VARCHAR(255) NULL,
                `is_global` VARCHAR(255) NULL,
                `visible_settings` VARCHAR(255) NULL,
                `category_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `popup_trigger` VARCHAR(255) NULL,
                `popup_time` INT(11) NULL,
                `popup_scroll` INT(11) NULL,
                `height_mobile` INT(11) NULL,
                `height_desktop` INT(11) NULL,
                `media_background_color` VARCHAR(255) NULL,
                `image_position` VARCHAR(255) NULL,
                `image_fit` VARCHAR(255) NULL,
                `image_alignment` VARCHAR(255) NULL,
                `image_mobile_settings` VARCHAR(255) NULL,
                `background_color` VARCHAR(255) NULL,
                `close_button_color` VARCHAR(255) NULL,
                `close_button_hover_color` VARCHAR(255) NULL,
                `promotion_color` VARCHAR(255) NULL,

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

                `submit_button_background_color` VARCHAR(255) NULL,
                `submit_button_background_hover_color` VARCHAR(255) NULL,
                `submit_button_text_color` VARCHAR(255) NULL,
                `submit_button_text_hover_color` VARCHAR(255) NULL,
                `non_submit_text_color` VARCHAR(255) NULL,
                `non_submit_text_hover_color` VARCHAR(255) NULL,
                `popup_border_radius` INT(11) NULL,
                `submit_button_border_radius` INT(11) NULL,
                `content_alignment` VARCHAR(255) NULL,

                `promotion_active` VARCHAR(255) NULL,
                `promotion_show_valid_until` VARCHAR(255) NULL,
                `promotion_id` BINARY(16) NULL,

                `register_media_image_id` BINARY(16) NULL,

                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `fk.marketing_essentials_register_popup.promotion_id` FOREIGN KEY (`promotion_id`)
                    REFERENCES `promotion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `fk.marketing_essentials_register_popup.register_media_image_id` FOREIGN KEY (`register_media_image_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_register_popup_translation` (
              `neno_marketing_essentials_register_popup_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              `name` VARCHAR(255),
              `headline` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
              `subline` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
              `text` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
              `promotion_text_valid_until` VARCHAR(255) NULL,
              `text_submit_button` VARCHAR(255) NULL,
              `text_non_submit` VARCHAR(255) NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`neno_marketing_essentials_register_popup_id`, `language_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_tabs` (
                `id` BINARY(16) NOT NULL,
                `is_global` VARCHAR(255) NULL,
                `display` VARCHAR(255) NULL,
                `category_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `favicon_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `neno_marketing_essentials_tabs_translation` (
                `neno_marketing_essentials_tabs_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                `name` VARCHAR(255),
                `text` VARCHAR(255) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`neno_marketing_essentials_tabs_id`, `language_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
