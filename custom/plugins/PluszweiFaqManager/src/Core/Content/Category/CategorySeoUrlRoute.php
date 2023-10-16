<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlMapping;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteConfig;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class CategorySeoUrlRoute implements SeoUrlRouteInterface
{
    public const ROUTE_NAME = 'pluszwei.faq.frontend.category';
    public const DEFAULT_TEMPLATE = '{% if category.navigation %}{% for part in category.navigation.seoBreadcrumb %}{{ part }}/{% endfor %}{% endif%}{% for part in category.seoBreadcrumb %}{{ part }}/{% endfor %}';

    public function __construct(
        private readonly CategoryDefinition $categoryDefinition,
        private readonly CategoryBreadcrumbBuilder $breadcrumbBuilder,
        private readonly SeoUrlRouteInterface $navigationSeoUrlRoute
    ) {
    }

    public function getConfig(): SeoUrlRouteConfig
    {
        return new SeoUrlRouteConfig(
            $this->categoryDefinition,
            self::ROUTE_NAME,
            self::DEFAULT_TEMPLATE
        );
    }

    public function prepareCriteria(Criteria $criteria, SalesChannelEntity $salesChannel): void
    {
        $criteria->addAssociation('navigation');
    }

    public function getMapping(Entity $category, ?SalesChannelEntity $salesChannel): SeoUrlMapping
    {
        if (!$category instanceof CategoryEntity) {
            throw new \InvalidArgumentException('Expected CategoryEntity');
        }

        $breadcrumbs = $this->breadcrumbBuilder->build($category, $salesChannel, null);
        $categoryJson = $category->jsonSerialize();
        $categoryJson['seoBreadcrumb'] = $breadcrumbs;

        $navigation = $category->getNavigation();
        if ($navigation) {
            $navigationSeoUrlMapping = $this->navigationSeoUrlRoute->getMapping($navigation, $salesChannel);
            $seoPathInfo = $navigationSeoUrlMapping->getSeoPathInfoContext();
            $navigationJson = $seoPathInfo['category'];

            $categoryJson['navigation'] = $navigationJson;
        }

        return new SeoUrlMapping(
            $category,
            ['categoryId' => $category->getId()],
            [
                'category' => $categoryJson,
            ]
        );
    }
}