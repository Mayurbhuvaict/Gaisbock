<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreBusinessHour;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\BusinessHourDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\BusinessWeekdayDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * Caused by shopware class inheritance
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreBusinessHourDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_business_hour';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StoreBusinessHourCollection::class;
    }

    public function getEntityClass(): string
    {
        return StoreBusinessHourEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

                (new BoolField('active', 'active'))->addFlags(new Required()),
                (new BoolField('annual', 'annual'))->addFlags(new Required()),
                (new StringField('special_date', 'specialDate')),

                new OneToManyAssociationField(
                    'store',
                    StoreDefinition::class,
                    'store_id',
                ),

                (new FkField('store_id', 'storeId', StoreDefinition::class))->addFlags(new Required()),
                new ManyToOneAssociationField(
                    'store',
                    'store_id',
                    StoreDefinition::class,
                    'id',
                    false
                ),

                (new FkField('business_hour_id', 'businessHourId', BusinessHourDefinition::class)),
                new ManyToOneAssociationField(
                    'businessHour',
                    'business_hour_id',
                    BusinessHourDefinition::class,
                    'id',
                    false
                ),

                (new FkField('business_weekday_id', 'businessWeekdayId', BusinessWeekdayDefinition::class)),
                new ManyToOneAssociationField(
                    'businessWeekday',
                    'business_weekday_id',
                    BusinessWeekdayDefinition::class,
                    'id',
                    false
                ),
            ]
        );
    }
}
