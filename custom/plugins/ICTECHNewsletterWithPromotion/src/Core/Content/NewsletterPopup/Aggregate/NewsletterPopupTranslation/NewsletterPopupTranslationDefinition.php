<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\NewsletterPopupDefinition;

class NewsletterPopupTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'newsletter_popup_translation';
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NewsletterPopupTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return NewsletterPopupTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return NewsletterPopupDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('headline', 'headline'))
                ->addFlags(new AllowHtml()),
            (new LongTextField('subline', 'subline'))
                ->addFlags(new AllowHtml()),
            new StringField('promotion_text_valid_until', 'promotionTextValidUntil'),
            new StringField('first_name_field_placeholder', 'firstNameFieldPlaceholder'),
            new StringField('last_name_field_placeholder', 'lastNameFieldPlaceholder'),
            new StringField('mail_field_placeholder', 'mailFieldPlaceholder'),
            new StringField('text_subscribe_button', 'textSubscribeButton'),
            new StringField('text_non_subscribe', 'textNonSubscribe'),
        ]);
    }
}
