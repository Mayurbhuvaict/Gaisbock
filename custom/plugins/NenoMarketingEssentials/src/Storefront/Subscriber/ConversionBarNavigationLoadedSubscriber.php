<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Neno\MarketingEssentials\Storefront\Service\ConversionBarLoader;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConversionBarNavigationLoadedSubscriber implements EventSubscriberInterface {
    public function __construct(private readonly ConversionBarLoader $conversionBarLoader)
    {
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NavigationPageLoadedEvent::class => 'onNavigationLoaded',
        ];
    }

    public function onNavigationLoaded(NavigationPageLoadedEvent $event): void
    {
        $barResult = $this->conversionBarLoader->loadForSalesChannel(
            $event->getContext(),
            $event->getSalesChannelContext());

        if ($barResult->count()) {
            $event->getPage()->addExtension('nme_conversion_bar', $barResult);
        }
    }
}
