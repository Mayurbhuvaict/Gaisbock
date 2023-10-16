<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilterValueDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_sl_filter_value';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return FilterValueEntity::class;
    }

    public function getCollectionClass(): string
    {
        return FilterValueCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('filter_id', 'filterId', FilterDefinition::class)),
            (new ManyToOneAssociationField('filter', 'filter_id', FilterDefinition::class)),

            new StringField('value', 'value')
        ]);
    }
}
