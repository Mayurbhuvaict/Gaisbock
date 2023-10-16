<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm;

use NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\Aggregate\ContactFormTranslation\ContactFormTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ContactFormDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_locator_contact_form';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ContactFormCollection::class;
    }

    public function getEntityClass(): string
    {
        return ContactFormEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new BoolField('active', 'active')),
                (new StringField('type', 'type'))->addFlags(new Required()),
                (new BoolField('required', 'required')),
                (new IntField('position', 'position')),

                // Translations
                new TranslatedField('label'),
                new TranslatedField('value'),

                (new TranslationsAssociationField(
                    ContactFormTranslationDefinition::class, 'neti_store_locator_contact_form_id'
                ))->addFlags(new Required()),
            ]
        );
    }
}
