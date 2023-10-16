<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article;


use Pluszwei\FaqManager\NavigationAssignment\UpdateNavigationPage;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityDeletedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SeoUrlUpdater $seoUrlUpdater,
        private readonly EntityRepository $categoryRepository,
        private readonly UpdateNavigationPage $updateNavigationPage
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'pluszwei_faq_article.written' => 'onEntityWritten',
            'pluszwei_faq_article.deleted' => 'onEntityDeleted',
        ];
    }

    public function onEntityDeleted(EntityDeletedEvent $event)
    {
        $this->seoUrlUpdater->update(ArticleSeoUrlRoute::ROUTE_NAME, $event->getIds());
    }

    public function onEntityWritten(EntityWrittenEvent $event)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true))
            ->addFilter(new EqualsFilter('type', 'faq'));
        /** @var \Shopware\Core\Content\Category\CategoryEntity $navigation */
        $context = $event->getContext();
        $navigation = $this->categoryRepository->search($criteria, $context)->first();
        if ($navigation) {
            $this->updateNavigationPage->set($navigation->getId(), $context);
        }
    }
}