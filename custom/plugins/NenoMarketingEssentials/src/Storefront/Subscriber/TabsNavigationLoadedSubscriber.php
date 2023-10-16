<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class TabsNavigationLoadedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityRepository $tabsRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NavigationPageLoadedEvent::class => 'listenToNavigationLoaded',
        ];
    }

    public function listenToNavigationLoaded(NavigationPageLoadedEvent $event): void
    {
        $request = $event->getRequest();
        $context = $event->getSalesChannelContext();

        // special tab

        $categoryId = $request->get('navigationId', $context->getSalesChannel()->getNavigationCategoryId());

        $criteria = (new Criteria())
            ->addFilter(new MultiFilter(
                    MultiFilter::CONNECTION_AND,
                    [
                        new EqualsFilter('isGlobal', 0),
                        new EqualsFilter('display', 'category'),
                        new EqualsFilter('categoryId', $categoryId)
                    ]
                )
            );

        $tab = $this->tabsRepository
            ->search($criteria, $event->getContext())
            ->getEntities();

        if ($tab->count()) {
            $event->getPage()->addExtension('nme_tabs', $tab);
            return;
        }

        // global tab

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('isGlobal', 1));
        $tab = $this->tabsRepository
            ->search($criteria, $event->getContext())
            ->getEntities();

        if ($tab->count()) {
            $event->getPage()->addExtension('nme_tabs', $tab);
            return;
        }
    }
}
