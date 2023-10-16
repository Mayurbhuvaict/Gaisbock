<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                       add(SeoFaqQuestionsEntity $entity)
 * @method void                       set(string $key, SeoFaqQuestionsEntity $entity)
 * @method SeoFaqQuestionsEntity[]    getIterator()
 * @method SeoFaqQuestionsEntity[]    getElements()
 * @method SeoFaqQuestionsEntity|null get(string $key)
 * @method SeoFaqQuestionsEntity|null first()
 * @method SeoFaqQuestionsEntity|null last()
 */
class SeoFaqQuestionsCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SeoFaqQuestionsEntity::class;
    }
}
