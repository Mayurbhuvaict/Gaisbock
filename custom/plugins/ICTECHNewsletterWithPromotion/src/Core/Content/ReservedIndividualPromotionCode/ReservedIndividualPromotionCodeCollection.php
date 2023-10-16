<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\ReservedIndividualPromotionCode;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 *
 * @method void                add(ReservedIndividualPromotionCodeEntity $entity)
 * @method void                set(string $key, ReservedIndividualPromotionCodeEntity $entity)
 * @method ReservedIndividualPromotionCodeEntity[]    getIterator()
 * @method ReservedIndividualPromotionCodeEntity[]    getElements()
 * @method ReservedIndividualPromotionCodeEntity|null get(string $key)
 * @method ReservedIndividualPromotionCodeEntity|null first()
 * @method ReservedIndividualPromotionCodeEntity|null last()
 */
class ReservedIndividualPromotionCodeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ReservedIndividualPromotionCodeEntity::class;
    }
}
