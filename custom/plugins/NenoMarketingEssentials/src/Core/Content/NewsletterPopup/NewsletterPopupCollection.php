<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\NewsletterPopup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(NewsletterPopupEntity $entity)
 * @method void              set(string $key, NewsletterPopupEntity $entity)
 * @method NewsletterPopupEntity[]    getIterator()
 * @method NewsletterPopupEntity[]    getElements()
 * @method NewsletterPopupEntity|null get(string $key)
 * @method NewsletterPopupEntity|null first()
 * @method NewsletterPopupEntity|null last()
 */
class NewsletterPopupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NewsletterPopupEntity::class;
    }
}
