<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Content\Product;

use Acris\ProductCustomerGroup\Custom\ProductCustomerGroupDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'acrisBlockCustomerGroup',
                CustomerGroupDefinition::class,
                ProductCustomerGroupDefinition::class,
                'product_id',
                'customer_group_id'
            ))->addFlags(new Inherited(), new ApiAware())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
