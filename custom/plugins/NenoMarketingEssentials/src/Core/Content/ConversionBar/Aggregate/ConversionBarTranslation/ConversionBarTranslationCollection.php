<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar\Aggregate\ConversionBarTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(ConversionBarTranslationEntity $entity)
 * @method void                         set(string $key, ConversionBarTranslationEntity $entity)
 * @method ConversionBarTranslationEntity[]    getIterator()
 * @method ConversionBarTranslationEntity[]    getElements()
 * @method ConversionBarTranslationEntity|null get(string $key)
 * @method ConversionBarTranslationEntity|null first()
 * @method ConversionBarTranslationEntity|null last()
 */
class ConversionBarTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ConversionBarTranslationEntity::class;
    }
}
