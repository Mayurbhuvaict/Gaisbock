<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup;

use Shopware\Core\Checkout\Promotion\PromotionDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Neno\MarketingEssentials\Core\Content\RegisterPopup\Aggregate\RegisterPopupTranslation\RegisterPopupTranslationDefinition;

class RegisterPopupDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neno_marketing_essentials_register_popup';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return RegisterPopupCollection::class;
    }

    public function getEntityClass(): string
    {
        return RegisterPopupEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('dev_mode', 'devMode')),
            (new StringField('storage_type', 'storageType')),
            (new BoolField('is_global', 'isGlobal')),
            (new StringField('visible_settings', 'visibleSettings')),
            (new IdField('category_id', 'categoryId')),
            (new IdField('product_id', 'productId')),
            (new StringField('popup_trigger', 'popupTrigger')),
            (new IntField('popup_time', 'popupTime')),
            (new IntField('popup_scroll', 'popupScroll')),
            (new IntField('height_mobile', 'heightMobile')),
            (new IntField('height_desktop', 'heightDesktop')),

            // translatable fields
            new TranslatedField('name'),
            new TranslatedField('headline'),
            new TranslatedField('subline'),
            new TranslatedField('promotionTextValidUntil'),
            new TranslatedField('text'),
            new TranslatedField('textSubmitButton'),
            new TranslatedField('textNonSubmit'),

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

            (new StringField('submit_button_background_color', 'submitButtonBackgroundColor')),
            (new StringField('submit_button_background_hover_color', 'submitButtonBackgroundHoverColor')),
            (new StringField('submit_button_text_color', 'submitButtonTextColor')),
            (new StringField('submit_button_text_hover_color', 'submitButtonTextHoverColor')),
            (new StringField('non_submit_text_color', 'nonSubmitTextColor')),
            (new StringField('non_submit_text_hover_color', 'nonSubmitTextHoverColor')),
            (new IntField('popup_border_radius', 'popupBorderRadius')),
            (new IntField('submit_button_border_radius', 'submitButtonBorderRadius')),
            (new StringField('content_alignment', 'contentAlignment')),

            (new BoolField('promotion_active', 'promotionActive')),
            (new BoolField('promotion_show_valid_until', 'promotionShowValidUntil')),
            new FkField('promotion_id', 'promotionId', PromotionDefinition::class),
            new OneToOneAssociationField('promotion', 'promotion_id', 'id', PromotionDefinition::class, true),

            new FkField('register_media_image_id', 'registerMediaImageId', MediaDefinition::class),
            new ManyToOneAssociationField('registerMediaImage', 'register_media_image_id', MediaDefinition::class, 'id', true),

            new TranslationsAssociationField(RegisterPopupTranslationDefinition::class, 'neno_marketing_essentials_register_popup_id'),
        ]);
    }
}
