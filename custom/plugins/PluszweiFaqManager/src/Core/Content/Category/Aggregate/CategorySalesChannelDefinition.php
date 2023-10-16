<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category\Aggregate;


use Pluszwei\FaqManager\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class CategorySalesChannelDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'pluszwei_faq_category_sales_channel';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function isVersionAware(): bool
    {
        return true;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('pluszwei_faq_category_id', 'categoryId', CategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(CategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('category', 'pluszwei_faq_category_id', CategoryDefinition::class, 'id', false),
            new ManyToOneAssociationField('salesChannel', 'sales_channel_id', SalesChannelDefinition::class, 'id', false),
        ]);
    }
}