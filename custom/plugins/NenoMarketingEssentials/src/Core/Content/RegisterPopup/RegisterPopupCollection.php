<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(RegisterPopupEntity $entity)
 * @method void              set(string $key, RegisterPopupEntity $entity)
 * @method RegisterPopupEntity[]    getIterator()
 * @method RegisterPopupEntity[]    getElements()
 * @method RegisterPopupEntity|null get(string $key)
 * @method RegisterPopupEntity|null first()
 * @method RegisterPopupEntity|null last()
 */
class RegisterPopupCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return RegisterPopupEntity::class;
    }
}
