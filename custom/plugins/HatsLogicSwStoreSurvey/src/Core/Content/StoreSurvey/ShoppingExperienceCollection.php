<?php declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Core\Content\StoreSurvey;


use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method string              getExpectedClass()
 */
class ShoppingExperienceCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ShoppingExperienceEntity::class;
    }
}
