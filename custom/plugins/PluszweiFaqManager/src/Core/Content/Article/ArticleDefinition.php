<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article;


use Pluszwei\FaqManager\Core\Content\Article\Aggregate\ArticleSalesChannelDefinition;
use Pluszwei\FaqManager\Core\Content\Article\Translation\ArticleTranslationDefinition;
use Pluszwei\FaqManager\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Seo\SeoUrl\SeoUrlDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class ArticleDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'pluszwei_faq_article';

    public function getEntityName(): string
    {
        return static::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new BoolField('active', 'active'),
            new BoolField('featured', 'featured'),

            (new FkField('media_id', 'mediaId', MediaDefinition::class)),
            (new OneToOneAssociationField('media', 'media_id', 'id', MediaDefinition::class, true)),

            (new TranslatedField('title'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new TranslatedField('content'),
            new TranslatedField('teaser'),
            new TranslatedField('metaTitle'),
            new TranslatedField('metaDescription'),
            new TranslatedField('keywords'),
            (new TranslatedField('url')),

            (new IdField('category_id', 'categoryId'))->addFlags(new Required()),
            (new IdField('section_id', 'sectionId'))->addFlags(new Required()),

            (new TranslationsAssociationField(ArticleTranslationDefinition::class,
                'pluszwei_faq_article_id'))->addFlags(new Required()),
            (new OneToManyAssociationField('seoUrls', SeoUrlDefinition::class,
                'foreign_key')),
            (new ManyToOneAssociationField('category', 'category_id', CategoryDefinition::class))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new ManyToOneAssociationField('section', 'section_id', CategoryDefinition::class))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new ManyToManyAssociationField('salesChannels', SalesChannelDefinition::class, ArticleSalesChannelDefinition::class, 'pluszwei_faq_article_id', 'sales_channel_id'))->addFlags(new CascadeDelete()),
        ]);
    }

    public function getEntityClass(): string
    {
        return ArticleEntity::class;
    }

    public function getCollectionClass(): string
    {
        return ArticleCollection::class;
    }
}