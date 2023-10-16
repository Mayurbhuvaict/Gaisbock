<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard\Aggregate\GiftcardTransaction;

use Laenen\Giftcard\Content\Giftcard\GiftcardDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
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
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class GiftcardTransactionDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'lae_giftcard_transaction';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return GiftcardTransactionEntity::class;
    }

    public function getCollectionClass(): string
    {
        return GiftcardTransactionCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('giftcard_id', 'giftcardId', GiftcardDefinition::class))->addFlags(new ApiAware(),
                new Required()),
            (new ManyToOneAssociationField('giftcard', 'giftcard_id',
                GiftcardDefinition::class))->addFlags(new ApiAware()),

            (new FloatField('amount', 'amount'))->addFlags(new ApiAware()),

            (new FkField('order_id', 'orderId', OrderDefinition::class))->addFlags(new ApiAware()),
            (new ReferenceVersionField(OrderDefinition::class))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('order', 'order_id', OrderDefinition::class, 'id')),

            (new LongTextField('comment', 'comment'))->addFlags(new ApiAware()),

            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
