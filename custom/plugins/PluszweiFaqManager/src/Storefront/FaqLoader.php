<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Storefront;


use Doctrine\DBAL\Connection;
use Pluszwei\FaqManager\Core\Content\Article\ArticleAvailableFilter;
use Pluszwei\FaqManager\Core\Content\Article\ArticleCollection;
use Pluszwei\FaqManager\Core\Content\Article\ArticleEntity;
use Pluszwei\FaqManager\Core\Content\Category\CategoryAvailableFilter;
use Pluszwei\FaqManager\Core\Content\Category\CategoryCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class FaqLoader
{
    public function __construct(
        private readonly EntityRepository $articleRepository,
        private readonly EntityRepository $categoryRepository,
        private readonly Connection $connection
    ) {
    }

    public function allFeaturedArticles(int $limit, SalesChannelContext $salesChannelContext): EntityCollection
    {
        $context = $salesChannelContext->getContext();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', false))
            ->addFilter(new EqualsFilter('level', 2));
        $inactiveSectionIds = $this->categoryRepository->searchIds($criteria, $context)->getIds();

        $aCriteria = new Criteria();
        $aCriteria->setLimit($limit);
        $aCriteria->addSorting(new FieldSorting('updatedAt', 'DESC'));
        $aCriteria->addFilter(new EqualsFilter('featured', true));
        $aCriteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));
        if (count($inactiveSectionIds)) {
            $aCriteria->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [
                new EqualsAnyFilter('sectionId', $inactiveSectionIds)
            ]));
        }

        return $this->articleRepository->search($aCriteria, $context)->getEntities();
    }

    public function activeCategories(SalesChannelContext $salesChannelContext, $ids = []): EntityCollection
    {
        $context = $salesChannelContext->getContext();
        $sql = <<<SQL
SELECT DISTINCT
	LOWER(HEX(c.id)) as id
FROM
	pluszwei_faq_article AS a
	LEFT JOIN pluszwei_faq_category AS s ON a.section_id = s.id
	LEFT JOIN pluszwei_faq_category AS c ON s.parent_id = c.id
WHERE
	a.active = TRUE
	AND s.active = TRUE
SQL;
        $result = $this->connection->fetchAllAssociative($sql);
        $resultIds = array_column($result, 'id');
        if (!count($resultIds)) {
            return new CategoryCollection([]);
        }
        $criteria = new Criteria($resultIds);
        $criteria->addFilter(new CategoryAvailableFilter($salesChannelContext->getSalesChannelId()));
        if (count($ids)) {
            $criteria->addFilter(new EqualsAnyFilter('id', $ids));
        }
        /** @var CategoryCollection $entities */
        $entities = $this->categoryRepository->search($criteria, $context)->getEntities();
        $entities->sortByPosition();

        return $entities;
    }

    public function categoryById($id, SalesChannelContext $salesChannelContext)
    {
        $criteria = new Criteria([$id]);
        $criteria->addFilter(new CategoryAvailableFilter($salesChannelContext->getSalesChannelId()));
        $criteria->addAssociation('navigation');

        return $this->categoryRepository->search($criteria, $salesChannelContext->getContext())->first();
    }

    public function activeSections($categoryId, SalesChannelContext $salesChannelContext, $ids = []): EntityCollection
    {
        if (count($ids)) {
            $criteria = new Criteria($ids);
        } else {
            $criteria = new Criteria();
        }
        $criteria->addFilter(new EqualsFilter('parentId', $categoryId))
            ->addFilter(new CategoryAvailableFilter($salesChannelContext->getSalesChannelId()))
            ->getAssociation('sectionArticles')->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));

        /** @var CategoryCollection $entities */
        $entities = $this->categoryRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
        $entities->sortByPosition();

        return $entities;
    }

    public function sectionActiveArticles($sectionId, SalesChannelContext $salesChannelContext): EntityCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('category');
        $criteria->addAssociation('category.navigation');
        $criteria->addAssociation('section');
        $criteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()))
            ->addFilter(new EqualsFilter('sectionId', $sectionId));

        return $this->articleRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
    }

    public function categoryFeaturedArticles($categoryId, SalesChannelContext $salesChannelContext, $excludes = []): EntityCollection
    {
        $sql = <<<SQL
SELECT LOWER(HEX(a.id)) as id
FROM
	pluszwei_faq_article AS a
	LEFT JOIN pluszwei_faq_category AS s ON a.section_id = s.id
	LEFT JOIN pluszwei_faq_category AS c ON s.parent_id = c.id
WHERE
	a.active = TRUE 
	AND a.featured = true
	AND s.active = TRUE
    AND c.id = :id
SQL;
        $result = $this->connection->fetchAllAssociative($sql, ['id' => Uuid::fromHexToBytes($categoryId)]);
        $ids = array_column($result, 'id');
        $ids = array_diff($ids, $excludes);

        if (!count($ids)) {
            return new ArticleCollection();
        }

        $criteria = new Criteria($ids);
        $criteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));

        return $this->articleRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
    }

    public function articleById($id, SalesChannelContext $salesChannelContext)
    {
        $criteria = new Criteria([$id]);
        $criteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));
        $criteria->addAssociation('category');
        $criteria->addAssociation('category.navigation');
        $criteria->addAssociation('section');

        return $this->articleRepository->search($criteria, $salesChannelContext->getContext())->first();
    }

    public function relatedArticles(ArticleEntity $article, SalesChannelContext $salesChannelContext): EntityCollection
    {
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('updatedAt', 'DESC'));
        $criteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));
        $criteria->addFilter(new EqualsFilter('sectionId', $article->getSectionId()));
        $criteria->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [
            new EqualsFilter('id', $article->getId())
        ]));

        return $this->articleRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
    }

    public function suggest(Request $request, SalesChannelContext $salesChannelContext): EntityCollection
    {
        $term = $request->get('search');
        $criteria = new Criteria();
        $criteria->addFilter(new ArticleAvailableFilter($salesChannelContext->getSalesChannelId()));
        $criteria->setTerm($term);

        return $this->articleRepository->search($criteria, $salesChannelContext->getContext())->getEntities();
    }

    public function sectionById($id, SalesChannelContext $salesChannelContext)
    {
        $criteria = new Criteria([$id]);
        $criteria->addFilter(new CategoryAvailableFilter($salesChannelContext->getSalesChannelId()));

        return $this->categoryRepository->search($criteria, $salesChannelContext->getContext())->first();
    }
}