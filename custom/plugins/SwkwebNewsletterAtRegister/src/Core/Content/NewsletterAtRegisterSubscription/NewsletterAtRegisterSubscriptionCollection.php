<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Core\Content\NewsletterAtRegisterSubscription;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<NewsletterAtRegisterSubscriptionEntity>
 */
class NewsletterAtRegisterSubscriptionCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NewsletterAtRegisterSubscriptionEntity::class;
    }
}
