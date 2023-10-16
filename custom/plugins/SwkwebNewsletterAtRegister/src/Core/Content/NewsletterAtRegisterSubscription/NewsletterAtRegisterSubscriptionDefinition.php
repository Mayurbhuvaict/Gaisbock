<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Core\Content\NewsletterAtRegisterSubscription;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class NewsletterAtRegisterSubscriptionDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'swkweb_newsletter_at_register_subscription';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return NewsletterAtRegisterSubscriptionEntity::class;
    }

    public function getCollectionClass(): string
    {
        return NewsletterAtRegisterSubscriptionCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('customer_id', 'id', CustomerDefinition::class))->addFlags(new Required(), new PrimaryKey()),

            new OneToOneAssociationField('customer', 'customer_id', 'id', CustomerDefinition::class, false),
        ]);
    }

    protected function defaultFields(): array
    {
        return [];
    }
}
