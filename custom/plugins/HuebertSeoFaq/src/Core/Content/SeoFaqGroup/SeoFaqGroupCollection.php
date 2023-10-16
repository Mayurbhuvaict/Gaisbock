<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(SeoFaqGroupEntity $entity)
 * @method void              set(string $key, SeoFaqGroupEntity $entity)
 * @method SeoFaqGroupEntity[]    getIterator()
 * @method SeoFaqGroupEntity[]    getElements()
 * @method SeoFaqGroupEntity|null get(string $key)
 * @method SeoFaqGroupEntity|null first()
 * @method SeoFaqGroupEntity|null last()
 */
class SeoFaqGroupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SeoFaqGroupEntity::class;
    }
}
