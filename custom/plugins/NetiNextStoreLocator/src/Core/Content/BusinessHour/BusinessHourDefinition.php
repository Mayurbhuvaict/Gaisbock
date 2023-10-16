<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\Aggregate\BusinessHourTranslation\BusinessHourTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * Caused by shopware class inheritance
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BusinessHourDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_business_hour';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return BusinessHourCollection::class;
    }

    public function getEntityClass(): string
    {
        return BusinessHourEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

                // Time
                (new StringField('start', 'start'))->addFlags(new Required()),
                (new StringField('end', 'end'))->addFlags(new Required()),

                // Translations
                (new TranslatedField('description')),
                (new TranslationsAssociationField(
                    BusinessHourTranslationDefinition::class, 'neti_business_hour_id'
                ))->addFlags(new Required()),
            ]
        );
    }
}
