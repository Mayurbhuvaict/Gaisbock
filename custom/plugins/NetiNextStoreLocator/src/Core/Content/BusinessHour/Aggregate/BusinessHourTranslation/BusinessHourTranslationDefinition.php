<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\Aggregate\BusinessHourTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\BusinessHourDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * Caused by shopware class inheritance
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BusinessHourTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = BusinessHourDefinition::ENTITY_NAME . '_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return BusinessHourTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return BusinessHourTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return BusinessHourDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new StringField('description', 'description')),
            ]
        );
    }
}
