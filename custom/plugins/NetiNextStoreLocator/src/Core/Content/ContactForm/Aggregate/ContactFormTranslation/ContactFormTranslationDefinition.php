<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\Aggregate\ContactFormTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\ContactFormDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ContactFormTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = ContactFormDefinition::ENTITY_NAME . '_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ContactFormTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ContactFormTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return ContactFormDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new StringField('label', 'label'))->addFlags(new Required()),
                (new LongTextField('value', 'value'))->addFlags(new AllowHtml()),
            ]
        );
    }
}
