<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\Aggregate\BusinessWeekdayTranslation\BusinessWeekdayTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * Caused by shopware class inheritance
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BusinessWeekdayDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_business_weekday';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return BusinessWeekdayCollection::class;
    }

    public function getEntityClass(): string
    {
        return BusinessWeekdayEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new IntField('number', 'number'))->addFlags(new Required()),

                // Translations
                (new TranslatedField('name')),

                (new TranslationsAssociationField(
                    BusinessWeekdayTranslationDefinition::class, 'neti_business_weekday_id'
                ))->addFlags(new Required()),
            ]
        );
    }
}
