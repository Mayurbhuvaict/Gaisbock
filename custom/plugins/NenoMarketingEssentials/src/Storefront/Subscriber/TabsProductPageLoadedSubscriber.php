<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TabsProductPageLoadedSubscriber implements EventSubscriberInterface
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
            ProductPageLoadedEvent::class => 'listenToProductLoaded',
        ];
    }

    public function listenToProductLoaded(ProductPageLoadedEvent $event): void
    {
        $request = $event->getRequest();
        $context = $event->getSalesChannelContext();

        // special tab

        $productId = $request->get('productId', $event->getPage()->getProduct()->getId());

        $criteria = (new Criteria())
            ->addFilter(new MultiFilter(
                    MultiFilter::CONNECTION_AND,
                    [
                        new EqualsFilter('isGlobal', 0),
                        new EqualsFilter('display', 'product'),
                        new EqualsFilter('productId', $productId)
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
        }
    }
}
