<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Pluszwei\FaqManager\NavigationAssignment\UpdateNavigationPage;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityDeletedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategorySubscriber implements EventSubscriberInterface
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
            'pluszwei_faq_category.written' => 'onEntityWritten',
            'pluszwei_faq_category.deleted' => 'onEntityDeleted',
        ];
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

    public function onEntityDeleted(EntityDeletedEvent $event): void
    {
        $this->seoUrlUpdater->update(CategorySeoUrlRoute::ROUTE_NAME, $event->getIds());
    }
}