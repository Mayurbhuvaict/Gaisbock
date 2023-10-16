<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\NewsletterPopup;

use Shopware\Core\Checkout\Promotion\PromotionDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Neno\MarketingEssentials\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation\NewsletterPopupTranslationDefinition;

class NewsletterPopupDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neno_marketing_essentials_newsletter_popup';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NewsletterPopupCollection::class;
    }

    public function getEntityClass(): string
    {
        return NewsletterPopupEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('dev_mode', 'devMode')),
            (new StringField('storage_type', 'storageType')),
            (new BoolField('is_global', 'isGlobal')),
            (new StringField('visible_settings', 'visibleSettings')),
            (new IdField('category_id', 'categoryId'))->addFlags(new Required()),
            (new IdField('product_id', 'productId')),
            (new StringField('popup_trigger', 'popupTrigger')),
            (new IntField('popup_time', 'popupTime')),
            (new IntField('popup_scroll', 'popupScroll')),
            (new IntField('height_mobile', 'heightMobile')),
            (new IntField('height_desktop', 'heightDesktop')),
            (new BoolField('show_first_name', 'showFirstName')),
            (new BoolField('show_last_name', 'showLastName')),

            // translatable fields
            new TranslatedField('name'),
            new TranslatedField('headline'),
            new TranslatedField('subline'),
            new TranslatedField('promotionTextValidUntil'),
            new TranslatedField('textSubscribeButton'),
            new TranslatedField('textNonSubscribe'),
            new TranslatedField('firstNameFieldPlaceholder'),
            new TranslatedField('lastNameFieldPlaceholder'),
            new TranslatedField('mailFieldPlaceholder'),

            // Headline typography
            (new StringField('headline_font_family', 'headlineFontFamily')),
            (new IntField('headline_font_size_mobile', 'headlineFontSizeMobile')),
            (new IntField('headline_line_height_mobile', 'headlineLineHeightMobile')),
            (new IntField('headline_font_size_tablet', 'headlineFontSizeTablet')),
            (new IntField('headline_line_height_tablet', 'headlineLineHeightTablet')),
            (new IntField('headline_font_size_desktop', 'headlineFontSizeDesktop')),
            (new IntField('headline_line_height_desktop', 'headlineLineHeightDesktop')),

            // Subline typography
            (new StringField('subline_font_family', 'sublineFontFamily')),
            (new IntField('subline_font_size_mobile', 'sublineFontSizeMobile')),
            (new IntField('subline_line_height_mobile', 'sublineLineHeightMobile')),
            (new IntField('subline_font_size_tablet', 'sublineFontSizeTablet')),
            (new IntField('subline_line_height_tablet', 'sublineLineHeightTablet')),
            (new IntField('subline_font_size_desktop', 'sublineFontSizeDesktop')),
            (new IntField('subline_line_height_desktop', 'sublineLineHeightDesktop')),

            // Promotion typography
            (new StringField('promotion_font_family', 'promotionFontFamily')),
            (new IntField('promotion_font_size_mobile', 'promotionFontSizeMobile')),
            (new IntField('promotion_line_height_mobile', 'promotionLineHeightMobile')),
            (new IntField('promotion_font_size_tablet', 'promotionFontSizeTablet')),
            (new IntField('promotion_line_height_tablet', 'promotionLineHeightTablet')),
            (new IntField('promotion_font_size_desktop', 'promotionFontSizeDesktop')),
            (new IntField('promotion_line_height_desktop', 'promotionLineHeightDesktop')),

            (new StringField('media_background_color', 'mediaBackgroundColor')),
            (new StringField('image_position', 'imagePosition')),
            (new StringField('image_fit', 'imageFit')),
            (new StringField('image_alignment', 'imageAlignment')),
            (new StringField('image_mobile_settings', 'imageMobileSettings')),
            (new StringField('background_color', 'backgroundColor')),
            (new StringField('close_button_color', 'closeButtonColor')),
            (new StringField('close_button_hover_color', 'closeButtonHoverColor')),
            (new StringField('promotion_color', 'promotionColor')),

            (new StringField('mail_field_border_color', 'mailFieldBorderColor')),
            (new StringField('first_name_field_border_color', 'firstNameFieldBorderColor')),
            (new StringField('last_name_field_border_color', 'lastNameFieldBorderColor')),
            (new StringField('subscribe_button_background_color', 'subscribeButtonBackgroundColor')),
            (new StringField('subscribe_button_background_hover_color', 'subscribeButtonBackgroundHoverColor')),
            (new StringField('subscribe_button_text_color', 'subscribeButtonTextColor')),
            (new StringField('subscribe_button_text_hover_color', 'subscribeButtonTextHoverColor')),
            (new StringField('non_subscribe_text_color', 'nonSubscribeTextColor')),
            (new StringField('non_subscribe_text_hover_color', 'nonSubscribeTextHoverColor')),
            (new IntField('popup_border_radius', 'popupBorderRadius')),
            (new IntField('mail_field_border_radius', 'mailFieldBorderRadius')),
            (new IntField('first_name_field_border_radius', 'firstNameFieldBorderRadius')),
            (new IntField('last_name_field_border_radius', 'lastNameFieldBorderRadius')),
            (new IntField('subscribe_button_border_radius', 'subscribeButtonBorderRadius')),
            (new StringField('content_alignment', 'contentAlignment')),

            (new BoolField('promotion_active', 'promotionActive')),
            (new BoolField('promotion_show_valid_until', 'promotionShowValidUntil')),
            new FkField('promotion_id', 'promotionId', PromotionDefinition::class),
            new OneToOneAssociationField('promotion', 'promotion_id', 'id', PromotionDefinition::class, true),

            new FkField('media_image_id', 'mediaImageId', MediaDefinition::class),
            new ManyToOneAssociationField('mediaImage', 'media_image_id', MediaDefinition::class, 'id', true),

            new TranslationsAssociationField(NewsletterPopupTranslationDefinition::class, 'neno_marketing_essentials_newsletter_popup_id'),
        ]);
    }
}
