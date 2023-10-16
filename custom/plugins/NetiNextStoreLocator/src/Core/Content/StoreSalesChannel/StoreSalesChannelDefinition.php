<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\StoreSalesChannel;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreSalesChannelDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'neti_store_sales_channel';

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
                (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(
                    new PrimaryKey(),
                    new Required()
                ),

                new ManyToOneAssociationField('store', 'store_id', StoreDefinition::class, 'id', false),
                new ManyToOneAssociationField(
                    'salesChannel',
                    'sales_channel_id',
                    SalesChannelDefinition::class,
                    'id',
                    false
                ),
            ]
        );
    }
}
