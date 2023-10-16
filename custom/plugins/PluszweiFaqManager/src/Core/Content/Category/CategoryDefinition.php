<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Pluszwei\FaqManager\Core\Content\Category\Aggregate\CategorySalesChannelDefinition;
use Pluszwei\FaqManager\Core\Content\Article\ArticleDefinition;
use Pluszwei\FaqManager\Core\Content\Category\Translation\CategoryTranslationDefinition;
use Shopware\Core\Content\Seo\SeoUrl\SeoUrlDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildCountField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TreeLevelField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TreePathField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\VersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class CategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'pluszwei_faq_category';

    public function getEntityName(): string
    {
        return static::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CategoryCollection::class;
    }

    public function getEntityClass(): string
    {
        return CategoryEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        $collection = new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new VersionField()),
            new ReferenceVersionField(self::class, 'parent_version_id'),
            (new IdField('parent_id', 'parentId')),


            (new FkField('after_category_id', 'afterCategoryId', self::class)),
            (new FkField('navigation_id', 'navigationId', self::class)),

            (new TranslatedField('breadcrumb'))->addFlags(new WriteProtected()),
            (new TreeLevelField('level', 'level')),
            (new TreePathField('path', 'path')),
            (new ChildCountField()),

            (new BoolField('active', 'active')),

            (new TranslatedField('name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING),  new Required()),
            (new TranslatedField('description')),
            (new TranslatedField('metaTitle')),
            (new TranslatedField('metaDescription')),
            (new TranslatedField('keywords')),

            (new ManyToOneAssociationField('parent', 'parent_id', __CLASS__)),
            (new ManyToOneAssociationField('navigation', 'navigation_id', \Shopware\Core\Content\Category\CategoryDefinition::class)),
            (new ChildrenAssociationField(self::class)),

            new TranslationsAssociationField(CategoryTranslationDefinition::class, 'pluszwei_faq_category_id'),
            (new OneToManyAssociationField('categoryArticles', ArticleDefinition::class, 'category_id')),
            (new OneToManyAssociationField('sectionArticles', ArticleDefinition::class, 'section_id')),
            (new OneToManyAssociationField('seoUrls', SeoUrlDefinition::class, 'foreign_key')),
            (new OneToManyAssociationField('navigationSalesChannels', SalesChannelDefinition::class, 'navigation_category_id')),
            (new OneToManyAssociationField('footerSalesChannels', SalesChannelDefinition::class, 'footer_category_id')),
            (new OneToManyAssociationField('serviceSalesChannels', SalesChannelDefinition::class, 'service_category_id')),
            (new ManyToManyAssociationField('salesChannels', SalesChannelDefinition::class, CategorySalesChannelDefinition::class, 'pluszwei_faq_category_id', 'sales_channel_id'))->addFlags(new CascadeDelete()),
        ]);

        return $collection;
    }
}