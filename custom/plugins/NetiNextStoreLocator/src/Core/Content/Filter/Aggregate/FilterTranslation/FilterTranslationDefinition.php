<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\FilterDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilterTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = FilterDefinition::ENTITY_NAME . '_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return FilterTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return FilterTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return FilterDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new StringField('title', 'title')),
            ]
        );
    }
}
