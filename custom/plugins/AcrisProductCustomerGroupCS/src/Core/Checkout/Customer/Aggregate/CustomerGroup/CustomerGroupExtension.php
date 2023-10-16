<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Checkout\Customer\Aggregate\CustomerGroup;

use Acris\ProductCustomerGroup\Custom\ProductCustomerGroupDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerGroupExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'product',
                ProductDefinition::class,
                ProductCustomerGroupDefinition::class,
                'customer_group_id',
                'product_id'
            ))->addFlags(new ApiAware())
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerGroupDefinition::class;
    }
}
