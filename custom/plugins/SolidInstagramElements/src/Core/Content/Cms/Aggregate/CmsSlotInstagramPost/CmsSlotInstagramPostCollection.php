<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Aggregate\CmsSlotInstagramPost;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<CmsSlotInstagramPostEntity>
 *
 * @method void               add(ExampleEntity $entity)
 * @method void               set(string $key, ExampleEntity $entity)
 * @method ExampleEntity[]    getIterator()
 * @method ExampleEntity[]    getElements()
 * @method ExampleEntity|null get(string $key)
 * @method ExampleEntity|null first()
 * @method ExampleEntity|null last()
 */
class CmsSlotInstagramPostCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CmsSlotInstagramPostEntity::class;
    }
}
