<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup\Aggregate\RegisterPopupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Neno\MarketingEssentials\Core\Content\RegisterPopup\RegisterPopupDefinition;

class RegisterPopupTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'neno_marketing_essentials_register_popup_translation';
    }

    public function getCollectionClass(): string
    {
        return RegisterPopupTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return RegisterPopupTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return RegisterPopupDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('headline', 'headline'))
                ->addFlags(new AllowHtml()),
            (new LongTextField('subline', 'subline'))
                ->addFlags(new AllowHtml()),
            (new LongTextField('text', 'text'))
                ->addFlags(new AllowHtml()),
            new StringField('promotion_text_valid_until', 'promotionTextValidUntil'),
            new StringField('text_submit_button', 'textSubmitButton'),
            new StringField('text_non_submit', 'textNonSubmit'),
        ]);
    }
}
