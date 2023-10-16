<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\NavigationAssignment;


use Pluszwei\FaqManager\Core\Content\Article\ArticleSeoUrlRoute;
use Pluszwei\FaqManager\Core\Content\Category\CategorySeoUrlRoute;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class UpdateNavigationPage
{
    public function __construct(
        private readonly EntityRepository $faqCategoryRepository,
        private readonly EntityRepository $faqArticleRepository,
        private readonly SeoUrlUpdater $seoUrlUpdater
    ) {
    }

    public function set($categoryId, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('navigationId', null));
        $ids = $this->faqCategoryRepository->searchIds($criteria, $context)->getIds();
        $params = array_map(function ($id) use ($categoryId) {
            return ['id' => $id, 'navigationId' => $categoryId];
        }, $ids);

        if (empty($ids)) {
            return;
        }

        $this->faqCategoryRepository->update($params, $context);
        $this->seoUrlUpdater->update(CategorySeoUrlRoute::ROUTE_NAME, $ids);

        $criteria = new Criteria();
        $ids = $this->faqArticleRepository->searchIds($criteria, $context)->getIds();
        if (!empty($ids)) {
            $this->seoUrlUpdater->update(ArticleSeoUrlRoute::ROUTE_NAME, $ids);
        }
    }

    public function remove($categoryId, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('navigationId', $categoryId));
        $ids = $this->faqCategoryRepository->searchIds($criteria, $context)->getIds();
        $params = array_map(function ($id) {
            return ['id' => $id, 'navigationId' => null];
        }, $ids);

        if (empty($ids)) {
            return;
        }

        $this->faqCategoryRepository->update($params, $context);
        $this->seoUrlUpdater->update(CategorySeoUrlRoute::ROUTE_NAME, $ids);

        $criteria = new Criteria();
        $ids = $this->faqArticleRepository->searchIds($criteria, $context)->getIds();
        if (!empty($ids)) {
            $this->seoUrlUpdater->update(ArticleSeoUrlRoute::ROUTE_NAME, $ids);
        }
    }
}