<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(TabsEntity $entity)
 * @method void              set(string $key, TabsEntity $entity)
 * @method TabsEntity[]    getIterator()
 * @method TabsEntity[]    getElements()
 * @method TabsEntity|null get(string $key)
 * @method TabsEntity|null first()
 * @method TabsEntity|null last()
 */
class TabsCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return TabsEntity::class;
    }
}
