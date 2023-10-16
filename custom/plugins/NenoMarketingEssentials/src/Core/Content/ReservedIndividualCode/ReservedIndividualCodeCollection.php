<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ReservedIndividualCode;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(ReservedIndividualCodeEntity $entity)
 * @method void              set(string $key, ReservedIndividualCodeEntity $entity)
 * @method ReservedIndividualCodeEntity[]    getIterator()
 * @method ReservedIndividualCodeEntity[]    getElements()
 * @method ReservedIndividualCodeEntity|null get(string $key)
 * @method ReservedIndividualCodeEntity|null first()
 * @method ReservedIndividualCodeEntity|null last()
 */
class ReservedIndividualCodeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ReservedIndividualCodeEntity::class;
    }
}
