<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Util\AfterSort;

/**
 * @method void                add(CategoryEntity $entity)
 * @method void                set(string $key, CategoryEntity $entity)
 * @method CategoryEntity[]    getIterator()
 * @method CategoryEntity[]    getElements()
 * @method CategoryEntity|null get(string $key)
 * @method CategoryEntity|null first()
 * @method CategoryEntity|null last()
 */
class CategoryCollection extends EntityCollection
{
    public function sortByPosition(): self
    {
        $this->elements = AfterSort::sort($this->elements, 'afterCategoryId');

        return $this;
    }

    protected function getExpectedClass(): string
    {
        return CategoryEntity::class;
    }

    public function filterByHasActiveArticles()
    {
        return $this->filter(function (CategoryEntity $category) {
                if ($category->getSectionArticles()->count() === 0) {
                    return false;
                }
                $activeArticles = $category->getSectionArticles()->filterByProperty('active', true);
                return $activeArticles->count() > 0;
            }
        );
    }
}