<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Custom;

use Shopware\Core\Content\Cms\DataAbstractionLayer\Field\SlotConfigField;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomersAlsoViewedDefinition extends EntityDefinition
{
    public CONST ENTITY_NAME = 'acris_customers_also_viewed';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getCollectionClass(): string
    {
        return CustomersAlsoViewedCollection::class;
    }
    public function getEntityClass(): string
    {
        return CustomersAlsoViewedEntity::class;
    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required(), new ApiAware()),
            (new IdField('product_id', 'productId'))->addFlags(new Required(), new ApiAware()),
            (new IdField('viewed_product_id', 'viewedProductId'))->addFlags(new Required(), new ApiAware()),
            (new StringField('session_id', 'sessionId'))->addFlags(new Required(), new ApiAware()),
            (new IdField('sales_channel_id', 'salesChannelId'))->addFlags(new Required(), new ApiAware()),
            (new IdField('row_hash', 'rowHash'))->addFlags(new ApiAware())
        ]);
    }
}
