<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article;


use Pluszwei\FaqManager\Core\Content\Category\CategorySeoUrlRoute;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlMapping;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteConfig;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class ArticleSeoUrlRoute implements SeoUrlRouteInterface
{
    public const ROUTE_NAME = 'pluszwei.faq.frontend.detail';
    public const DEFAULT_TEMPLATE = '{% if article.section.navigation %}{% for part in article.section.navigation.seoBreadcrumb %}{{ part }}/{% endfor %}{% endif%}{% for part in article.section.seoBreadcrumb %}{{ part }}/{% endfor %}{{ (article.translated.url ?? article.translated.title)|lower }}';

    public function __construct(
        private readonly ArticleDefinition $articleDefinition,
        private readonly CategorySeoUrlRoute $categorySeoUrlRoute
    ) {
    }

    public function getConfig(): SeoUrlRouteConfig
    {
        return new SeoUrlRouteConfig(
            $this->articleDefinition,
            self::ROUTE_NAME,
            self::DEFAULT_TEMPLATE
        );
    }

    public function prepareCriteria(Criteria $criteria, SalesChannelEntity $salesChannel): void
    {
        $criteria->addAssociation('section')
            ->addAssociation('section.navigation');
    }

    public function getMapping(Entity $article, ?SalesChannelEntity $salesChannel): SeoUrlMapping
    {
        if (!$article instanceof ArticleEntity) {
            throw new \InvalidArgumentException('Expected ArticleEntity');
        }

        $articleJson = $article->jsonSerialize();

        $section = $article->getSection();
        if ($section) {
            $articleJson['sectionBreadcrumb'] = $article->getSection()->getPlainBreadcrumb();

            $sectionSeoUrlMapping = $this->categorySeoUrlRoute->getMapping($section, $salesChannel);
            $seoPathInfo = $sectionSeoUrlMapping->getSeoPathInfoContext();
            $sectionJson = $seoPathInfo['category'];
            $articleJson['section'] = $sectionJson;
        } else {
            $articleJson['sectionBreadcrumb'] = [];
            $articleJson['section'] = [];
        }

        return new SeoUrlMapping(
            $article,
            ['articleId' => $article->getId()],
            [
                'article' => $articleJson,
            ]
        );
    }
}