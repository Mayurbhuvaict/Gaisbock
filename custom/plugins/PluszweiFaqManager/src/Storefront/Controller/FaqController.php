<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Storefront\Controller;


use Pluszwei\FaqManager\Core\Content\Article\ArticleEntity;
use Pluszwei\FaqManager\Core\Content\Category\CategoryEntity;
use Pluszwei\FaqManager\Storefront\DetailPageLoadedEvent;
use Pluszwei\FaqManager\Storefront\FaqLoader;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Page\GenericPageLoader;
use Shopware\Storefront\Page\Navigation\NavigationPage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class FaqController extends StorefrontController
{
    public function __construct(
        private readonly GenericPageLoader $genericPageLoader,
        private readonly SystemConfigService $systemConfigService,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }


    #[Route(path: '/pluszwei_faq', name: 'pluszwei.faq.frontend.index', defaults: ['_httpCache' => true], methods: ['GET'])]
    public function indexAction(Request $request, SalesChannelContext $salesChannelContext)
    {
        $faqLoader = $this->container->get(FaqLoader::class);
        $featuredArticles = $faqLoader->allFeaturedArticles(6, $salesChannelContext);
        $categories = $faqLoader->activeCategories($salesChannelContext);

        $media = null;
        $mediaId = $this->systemConfigService->get('PluszweiFaqManager.config.bannerImage');
        if ($mediaId) {
            /** @var EntityRepository $mediaRepository */
            $mediaRepository = $this->container->get('media.repository');
            $media = $mediaRepository->search(new Criteria([$mediaId]), $salesChannelContext->getContext())->first();
        }

        return $this->renderStorefront('@PluszweiFaqManager/storefront/page/faq/index.html.twig', [
            'categories' => $categories,
            'banner' => $media,
            'featuredArticles' => $featuredArticles
        ]);
    }

    #[Route(path: '/pluszwei_faq/search', name: 'frontend.pluszwei.faq.frontend.search', defaults: ['XmlHttpRequest' => true, '_httpCache' => true], methods: ['GET'])]
    public function searchAction(SalesChannelContext $salesChannelContext, Request $request)
    {
        $faqLoader = $this->container->get(FaqLoader::class);
        $articles = $faqLoader->suggest($request, $salesChannelContext);

        return $this->renderStorefront('@PluszweiFaqManager/storefront/page/faq/index/search-suggest.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(path: '/pluszwei_faq/{categoryId}', name: 'pluszwei.faq.frontend.category', defaults: ['_httpCache' => true], methods: ['GET'])]
    public function categoryAction(string $categoryId, Request $request, SalesChannelContext $salesChannelContext)
    {
        $faqLoader = $this->container->get(FaqLoader::class);
        /** @var CategoryEntity $category */
        $category = $faqLoader->categoryById($categoryId, $salesChannelContext);
        if (!$category) {
            throw new PageNotFoundException($categoryId);
        }

        if ($category->getLevel() > 1) {
            return $this->redirectToRoute('pluszwei.faq.frontend.category', ['categoryId' => $category->getParentId()]);
        }

        $featuredArticles = $faqLoader->categoryFeaturedArticles($categoryId, $salesChannelContext);
        $sections = $faqLoader->activeSections($categoryId, $salesChannelContext);

        $page = $this->genericPageLoader->load($request, $salesChannelContext);
        $page = NavigationPage::createFrom($page);

        $metaInformation = $page->getMetaInformation();
        if ($category->getTranslated()['metaTitle']) {
            $metaInformation->setMetaTitle($category->getTranslated()['metaTitle']);
        } else {
            $metaInformation->setMetaTitle($category->getTranslated()['name']);
        }
        if ($category->getTranslated()['metaDescription']) {
            $metaInformation->setMetaDescription($category->getTranslated()['metaDescription']);
        }
        if ($category->getTranslated()['keywords']) {
            $metaInformation->setMetaKeywords($category->getTranslated()['keywords']);
        }

        return $this->renderStorefront('@PluszweiFaqManager/storefront/page/faq/category/category.html.twig', [
            'category' => $category,
            'featuredArticles' => $featuredArticles,
            'sections' => $sections,
            'page' => $page
        ]);
    }

    #[Route(path: '/pluszwei_faq/detail/{articleId}', name: 'pluszwei.faq.frontend.detail', defaults: ['_httpCache' => true], methods: ['GET'])]
    public function detailAction(string $articleId, Request $request, SalesChannelContext $salesChannelContext)
    {
        $faqLoader = $this->container->get(FaqLoader::class);

        /** @var ArticleEntity $article */
        $article = $faqLoader->articleById($articleId, $salesChannelContext);
        if (!$article) {
            throw new PageNotFoundException($articleId);
        }

        $relatedArticles = $faqLoader->relatedArticles($article, $salesChannelContext);
        $featuredArticles = $faqLoader->categoryFeaturedArticles($article->getCategoryId(), $salesChannelContext, [$articleId]);
        $sections = $faqLoader->activeSections($article->getCategoryId(), $salesChannelContext);

        // page info
        $page = $this->genericPageLoader->load($request, $salesChannelContext);
        $page = NavigationPage::createFrom($page);

        $metaInformation = $page->getMetaInformation();
        if ($article->getTranslated()['metaTitle']) {
            $metaInformation->setMetaTitle($article->getTranslated()['metaTitle']);
        } else {
            $metaInformation->setMetaTitle($article->getTranslated()['title']);
        }
        if ($article->getTranslated()['metaDescription']) {
            $metaInformation->setMetaDescription($article->getTranslated()['metaDescription']);
        }
        if ($article->getTranslated()['keywords']) {
            $metaInformation->setMetaKeywords($article->getTranslated()['keywords']);
        }

        $this->eventDispatcher->dispatch(
            new DetailPageLoadedEvent($page, $salesChannelContext, $request)
        );

        return $this->renderStorefront('@PluszweiFaqManager/storefront/page/faq/detail/detail.html.twig', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'featuredArticles' => $featuredArticles,
            'sections' => $sections,
            'page' => $page
        ]);
    }
}