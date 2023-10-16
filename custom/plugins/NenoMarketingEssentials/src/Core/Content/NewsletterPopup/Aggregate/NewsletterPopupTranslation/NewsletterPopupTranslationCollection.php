<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(NewsletterPopupTranslationEntity $entity)
 * @method void                         set(string $key, NewsletterPopupTranslationEntity $entity)
 * @method NewsletterPopupTranslationEntity[]    getIterator()
 * @method NewsletterPopupTranslationEntity[]    getElements()
 * @method NewsletterPopupTranslationEntity|null get(string $key)
 * @method NewsletterPopupTranslationEntity|null first()
 * @method NewsletterPopupTranslationEntity|null last()
 */
class NewsletterPopupTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NewsletterPopupTranslationEntity::class;
    }
}
