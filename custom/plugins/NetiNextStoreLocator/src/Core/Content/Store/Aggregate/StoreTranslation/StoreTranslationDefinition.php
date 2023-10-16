<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = StoreDefinition::ENTITY_NAME . '_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return StoreTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return StoreTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return StoreDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new LongTextField('description', 'description'))->addFlags(new AllowHtml()),
                (new LongTextField('additional_information', 'additionalInformation'))->addFlags(new AllowHtml()),

                (new StringField('seo_title', 'seoTitle')),
                (new StringField('seo_url', 'seoUrl')),
                (new StringField('seo_description', 'seoDescription')),

                (new StringField('detail_title', 'detailTitle')),
                (new LongTextField('detail_description', 'detailDescription'))->addFlags(new AllowHtml()),
                (new LongTextField('opening_times', 'openingTimes'))->addFlags(new AllowHtml()),
            ]
        );
    }
}
