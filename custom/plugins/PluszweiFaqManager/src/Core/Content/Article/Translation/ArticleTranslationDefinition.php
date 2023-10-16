<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article\Translation;


use Pluszwei\FaqManager\Core\Content\Article\ArticleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ArticleTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'pluszwei_faq_article_translation';

    public function getEntityName(): string
    {
        return static::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('title', 'title'))->addFlags(new Required()),
            (new LongTextField('content', 'content'))->addFlags(new AllowHtml()),
            new StringField('teaser', 'teaser'),
            new StringField('meta_title', 'metaTitle'),
            new StringField('meta_description', 'metaDescription'),
            new StringField('keywords', 'keywords'),
            new StringField('url', 'url'),
        ]);
    }

    public function getCollectionClass(): string
    {
        return ArticleTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return ArticleTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return ArticleDefinition::class;
    }
}