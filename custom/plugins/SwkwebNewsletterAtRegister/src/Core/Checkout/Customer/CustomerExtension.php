<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Core\Checkout\Customer;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swkweb\NewsletterAtRegister\Core\Content\NewsletterAtRegisterSubscription\NewsletterAtRegisterSubscriptionDefinition;

class CustomerExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new OneToOneAssociationField('swkwebNewsletterAtRegisterSubscription', 'id', 'customer_id', NewsletterAtRegisterSubscriptionDefinition::class))
                ->addFlags(new CascadeDelete()),
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }
}
