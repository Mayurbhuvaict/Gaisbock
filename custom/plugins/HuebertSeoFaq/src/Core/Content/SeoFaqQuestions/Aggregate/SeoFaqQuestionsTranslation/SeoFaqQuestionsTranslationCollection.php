<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                                  add(SeoFaqQuestionsTranslationEntity $entity)
 * @method void                                  set(string $key, SeoFaqQuestionsTranslationEntity $entity)
 * @method SeoFaqQuestionsTranslationEntity[]    getIterator()
 * @method SeoFaqQuestionsTranslationEntity[]    getElements()
 * @method SeoFaqQuestionsTranslationEntity|null get(string $key)
 * @method SeoFaqQuestionsTranslationEntity|null first()
 * @method SeoFaqQuestionsTranslationEntity|null last()
 */
class SeoFaqQuestionsTranslationCollection extends EntityCollection
{
    public function filterByLanguageId(string $id): self
    {
        return $this->filter(function (SeoFaqQuestionsTranslationEntity $SeoFaqQuestionsTranslationEntity) use ($id) {
            return $SeoFaqQuestionsTranslationEntity->getLanguageId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return SeoFaqQuestionsTranslationEntity::class;
    }
}
