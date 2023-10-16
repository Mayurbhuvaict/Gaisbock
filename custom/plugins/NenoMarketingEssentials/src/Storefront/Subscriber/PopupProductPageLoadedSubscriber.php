<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PopupProductPageLoadedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityRepository $registerPopupRepository, private readonly EntityRepository $newsletterPopupRepository)
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

        // special popup

        $productId = $request->get('productId', $event->getPage()->getProduct()->getId());

        $criteria = (new Criteria())
            ->addFilter(new MultiFilter(
                MultiFilter::CONNECTION_AND,
                [
                    new EqualsFilter('isGlobal', 0),
                    new EqualsFilter('visibleSettings', 'product'),
                    new EqualsFilter('productId', $productId)
                ]
            )
        );

        $register_popup = $this->registerPopupRepository
            ->search($criteria, $event->getContext())
            ->getEntities();

        $newsletterPopupCriteria = (new Criteria())
            ->addFilter(new MultiFilter(
                MultiFilter::CONNECTION_AND,
                [
                    new EqualsFilter('isGlobal', 0),
                    new EqualsFilter('visibleSettings', 'product'),
                    new EqualsFilter('productId', $productId)
                ]
            )
        );

        $newsletter_popup = $this->newsletterPopupRepository
            ->search($newsletterPopupCriteria, $event->getContext())
            ->getEntities();

        if ($register_popup->count() or $newsletter_popup->count()) {
            $event->getPage()->addExtension('register_popup', $register_popup);
            $event->getPage()->addExtension('newsletter_popup', $newsletter_popup);
            return;
        }

        // global popup

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('isGlobal', 1));
        $register_popup = $this->registerPopupRepository
            ->search($criteria, $event->getContext())
            ->getEntities();

        $newsletterPopupCriteria = new Criteria();
        $newsletterPopupCriteria->addFilter(new EqualsFilter('isGlobal', 1));
        $newsletter_popup = $this->newsletterPopupRepository
            ->search($criteria, $event->getContext())
            ->getEntities();

        if ($register_popup or $newsletter_popup) {
            $event->getPage()->addExtension('register_popup', $register_popup);
            $event->getPage()->addExtension('newsletter_popup', $newsletter_popup);
        }
    }
}
