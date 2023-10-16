<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<GiftcardEntity>
 */
class GiftcardCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return GiftcardEntity::class;
    }
}
