<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Core\Content\StoreSurvey;


use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;

class ShoppingExperienceDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 's_plugin_hatslogic_shopping_experiences';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return ShoppingExperienceEntity::class;
    }

    public function getCollectionClass(): string
    {
        return ShoppingExperienceCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(new Required()),
            new FkField('customer_id', 'customerId', CustomerDefinition::class),
            (new IntField('points', 'points'))->addFlags(new Required()),
            new LongTextField('comment', 'comment'),
            new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id', false),
            new ManyToOneAssociationField('salesChannel', 'sales_channel_id', SalesChannelDefinition::class, 'id', false)
        ]);
    }
}
