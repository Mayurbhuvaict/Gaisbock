<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs\Aggregate\TabsTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(TabsTranslationEntity $entity)
 * @method void                         set(string $key, TabsTranslationEntity $entity)
 * @method TabsTranslationEntity[]    getIterator()
 * @method TabsTranslationEntity[]    getElements()
 * @method TabsTranslationEntity|null get(string $key)
 * @method TabsTranslationEntity|null first()
 * @method TabsTranslationEntity|null last()
 */
class TabsTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return TabsTranslationEntity::class;
    }
}
