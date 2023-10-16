<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard\Aggregate\GiftcardTransaction;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<GiftcardTransactionEntity>
 */
class GiftcardTransactionCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return GiftcardTransactionEntity::class;
    }
}
