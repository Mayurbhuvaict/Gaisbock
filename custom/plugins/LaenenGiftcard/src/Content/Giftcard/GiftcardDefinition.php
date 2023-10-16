<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard;

use Laenen\Giftcard\Content\Giftcard\Aggregate\GiftcardTransaction\GiftcardTransactionDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Currency\CurrencyDefinition;
use Shopware\Core\System\Language\LanguageDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class GiftcardDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'lae_giftcard';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return GiftcardEntity::class;
    }

    public function getCollectionClass(): string
    {
        return GiftcardCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey()),

            (new StringField('name', 'name'))->addFlags(new ApiAware()),
            (new LongTextField('description', 'description'))->addFlags(new ApiAware()),

            (new StringField('code', 'code'))->addFlags(new Required()),

            (new FloatField('balance', 'balance'))->addFlags(new ApiAware()),
            (new FloatField('initial_amount', 'initialAmount'))->addFlags(new ApiAware()),

            (new FkField('currency_id', 'currencyId', CurrencyDefinition::class))->addFlags(new ApiAware(),
                new Required()),
            (new ManyToOneAssociationField('currency', 'currency_id', CurrencyDefinition::class,
                'id'))->addFlags(new ApiAware()),

            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new ApiAware(),
                new Required()),
            (new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class,
                'id'))->addFlags(new ApiAware()),

            (new FkField('origin_order_id', 'originOrderId', OrderDefinition::class))->addFlags(new ApiAware()),
            (new ReferenceVersionField(OrderDefinition::class, 'origin_order_version_id'))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('originOrder', 'origin_order_id', OrderDefinition::class, 'id')),

            (new FkField('origin_product_id', 'originProductId', ProductDefinition::class))->addFlags(new ApiAware()),
            (new ReferenceVersionField(ProductDefinition::class, 'origin_product_version_id'))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('originProduct', 'origin_product_id', ProductDefinition::class, 'id')),

            (new FkField('sales_channel_id', 'salesChannelId',
                SalesChannelDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('salesChannel', 'sales_channel_id', SalesChannelDefinition::class, 'id')),

            (new OneToManyAssociationField('transactions', GiftcardTransactionDefinition::class,
                'giftcard_id'))->addFlags(new ApiAware()),

            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
