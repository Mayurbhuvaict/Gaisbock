<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(ConversionBarEntity $entity)
 * @method void              set(string $key, ConversionBarEntity $entity)
 * @method ConversionBarEntity[]    getIterator()
 * @method ConversionBarEntity[]    getElements()
 * @method ConversionBarEntity|null get(string $key)
 * @method ConversionBarEntity|null first()
 * @method ConversionBarEntity|null last()
 */
class ConversionBarCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ConversionBarEntity::class;
    }
}

