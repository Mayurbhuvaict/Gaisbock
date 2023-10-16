<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTag;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\Tag\TagDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreTagDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_tag';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new FkField('store_id', 'storeId', StoreDefinition::class))->addFlags(
                    new PrimaryKey(),
                    new Required()
                ),
                (new FkField('tag_id', 'tagId', TagDefinition::class))->addFlags(
                    new PrimaryKey(),
                    new Required()
                ),

                new ManyToOneAssociationField('store', 'store_id', StoreDefinition::class, 'id', false),
                new ManyToOneAssociationField(
                    'tag',
                    'tag_id',
                    TagDefinition::class,
                    'id',
                    false
                ),
            ]
        );
    }
}
