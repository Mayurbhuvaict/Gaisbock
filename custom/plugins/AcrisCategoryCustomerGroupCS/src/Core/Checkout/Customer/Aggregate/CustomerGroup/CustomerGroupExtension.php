<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Core\Checkout\Customer\Aggregate\CustomerGroup;

use Acris\CategoryCustomerGroup\Custom\CategoryCustomerGroupDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerGroupExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'category',
                CategoryDefinition::class,
                CategoryCustomerGroupDefinition::class,
                'customer_group_id',
                'category_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerGroupDefinition::class;
    }
}
