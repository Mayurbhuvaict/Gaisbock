<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTag\FilterTagDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTranslation\FilterTranslationDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue\FilterValueDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\CustomField\CustomFieldDefinition;
use Shopware\Core\System\Tag\TagDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilterDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'neti_sl_filter';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return FilterEntity::class;
    }

    public function getCollectionClass(): string
    {
        return FilterCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new BoolField('active', 'active'))->addFlags(new Required()),

            (new IntField('value_type', 'valueType'))->addFlags(new Required()),
            (new IntField('display_type', 'displayType'))->addFlags(new Required()),

            (new IntField('position', 'position'))->addFlags(new Required()),

            // Fields for value_type "tags"
            new ManyToManyAssociationField(
                'tags',
                TagDefinition::class,
                FilterTagDefinition::class,
                'filter_id',
                'tag_id'
            ),

            // Fields for value_type "custom_fields"
            new FkField('custom_field_id', 'customFieldId', CustomFieldDefinition::class),
            new ManyToOneAssociationField(
                'customField',
                'custom_field_id',
                CustomFieldDefinition::class,
            ),

            new OneToManyAssociationField(
                'values',
                FilterValueDefinition::class,
                'filter_id'
            ),

            // Translations

            (new TranslatedField('title')),

            (new TranslationsAssociationField(
                FilterTranslationDefinition::class, 'neti_sl_filter_id'
            ))->addFlags(new Required()),
        ]);
    }
}
