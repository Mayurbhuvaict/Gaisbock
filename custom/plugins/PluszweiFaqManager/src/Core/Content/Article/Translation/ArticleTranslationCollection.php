<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article\Translation;

/**
 * @method void                       add(ArticleTranslationEntity $entity)
 * @method void                       set(string $key, ArticleTranslationEntity $entity)
 * @method ArticleTranslationEntity[]    getIterator()
 * @method ArticleTranslationEntity[]    getElements()
 * @method ArticleTranslationEntity|null get(string $key)
 * @method ArticleTranslationEntity|null first()
 * @method ArticleTranslationEntity|null last()
 */
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ArticleTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ArticleTranslationEntity::class;
    }
}