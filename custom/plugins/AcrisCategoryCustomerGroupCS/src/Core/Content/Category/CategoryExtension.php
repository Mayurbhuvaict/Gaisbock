<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Core\Content\Category;

use Acris\CategoryCustomerGroup\Custom\CategoryCustomerGroupDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CategoryExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'customerGroup',
                CustomerGroupDefinition::class,
                CategoryCustomerGroupDefinition::class,
                'category_id',
                'customer_group_id'
            ))->addFlags(new ApiAware())
        );
    }

    public function getDefinitionClass(): string
    {
        return CategoryDefinition::class;
    }
}
