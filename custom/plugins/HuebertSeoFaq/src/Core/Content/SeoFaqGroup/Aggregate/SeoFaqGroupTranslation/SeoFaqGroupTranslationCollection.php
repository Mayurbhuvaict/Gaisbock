<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                                  add(SeoFaqGroupTranslationEntity $entity)
 * @method void                                  set(string $key, SeoFaqGroupTranslationEntity $entity)
 * @method SeoFaqGroupTranslationEntity[]    getIterator()
 * @method SeoFaqGroupTranslationEntity[]    getElements()
 * @method SeoFaqGroupTranslationEntity|null get(string $key)
 * @method SeoFaqGroupTranslationEntity|null first()
 * @method SeoFaqGroupTranslationEntity|null last()
 */
class SeoFaqGroupTranslationCollection extends EntityCollection
{
    public function filterByLanguageId(string $id): self
    {
        return $this->filter(function (SeoFaqGroupTranslationEntity $SeoFaqGroupTranslationEntity) use ($id) {
            return $SeoFaqGroupTranslationEntity->getLanguageId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return SeoFaqGroupTranslationEntity::class;
    }
}
