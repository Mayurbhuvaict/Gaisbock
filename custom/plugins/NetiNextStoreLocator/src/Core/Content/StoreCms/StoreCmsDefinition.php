<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreCms;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Content\Cms\CmsPageDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * Property is set in the parent class made by Shopware
 */
class StoreCmsDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_cms';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StoreCmsCollection::class;
    }

    public function getEntityClass(): string
    {
        return StoreCmsEntity::class;
    }

    public function getDefaults(): array
    {
        $defaults = parent::getDefaults();

        $defaults['position'] = 1;

        return $defaults;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new IntField('position', 'position'))->addFlags(new Required()),

                (new FkField('store_id', 'storeId', StoreDefinition::class))->addFlags(
                    new Required()
                ),
                new ManyToOneAssociationField(
                    'store',
                    'store_id',
                    StoreDefinition::class,
                    'id',
                    false
                ),

                (new FkField('cms_page_id', 'cmsPageId', CmsPageDefinition::class))->addFlags(new Required()),
                new ManyToOneAssociationField(
                    'cmsPage',
                    'cms_page_id',
                    CmsPageDefinition::class,
                    'id',
                    false
                ),
                (new ReferenceVersionField(CmsPageDefinition::class, 'cms_page_version_id'))->addFlags(new Required()),
            ]
        );
    }
}
