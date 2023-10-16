<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup\Aggregate\RegisterPopupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(RegisterPopupTranslationEntity $entity)
 * @method void                         set(string $key, RegisterPopupTranslationEntity $entity)
 * @method RegisterPopupTranslationEntity[]    getIterator()
 * @method RegisterPopupTranslationEntity[]    getElements()
 * @method RegisterPopupTranslationEntity|null get(string $key)
 * @method RegisterPopupTranslationEntity|null first()
 * @method RegisterPopupTranslationEntity|null last()
 */
class RegisterPopupTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return RegisterPopupTranslationEntity::class;
    }
}
