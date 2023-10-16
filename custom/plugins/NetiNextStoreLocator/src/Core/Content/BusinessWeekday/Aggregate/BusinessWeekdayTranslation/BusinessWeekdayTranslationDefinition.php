<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\Aggregate\BusinessWeekdayTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessWeekday\BusinessWeekdayDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * Caused by shopware class inheritance
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BusinessWeekdayTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = BusinessWeekdayDefinition::ENTITY_NAME . '_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return BusinessWeekdayTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return BusinessWeekdayTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return BusinessWeekdayDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new StringField('name', 'name'))->addFlags(new Required()),
            ]
        );
    }
}
