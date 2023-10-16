<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category\Translation;


use Pluszwei\FaqManager\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BreadcrumbField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ListField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CategoryTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'pluszwei_faq_category_translation';

    public function getEntityName(): string
    {
        return static::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return CategoryTranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return CategoryTranslationCollection::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return CategoryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new BreadcrumbField())->addFlags(new WriteProtected()),
            (new LongTextField('description', 'description'))->addFlags(new AllowHtml()),
            (new LongTextField('meta_title', 'metaTitle'))->addFlags(new AllowHtml()),
            (new LongTextField('meta_description', 'metaDescription'))->addFlags(new AllowHtml()),
            new StringField('keywords', 'keywords'),
        ]);
    }
}