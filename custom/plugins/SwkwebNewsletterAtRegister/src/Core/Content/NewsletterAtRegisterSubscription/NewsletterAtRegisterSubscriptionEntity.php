<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Core\Content\NewsletterAtRegisterSubscription;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class NewsletterAtRegisterSubscriptionEntity extends Entity
{
    use EntityIdTrait;

    protected ?CustomerEntity $customer = null;

    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }
}
