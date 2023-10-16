<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Neno\MarketingEssentials\Storefront\Service\ConversionBarLoader;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConversionBarProductPageLoadedSubscriber implements EventSubscriberInterface {
    public function __construct(private readonly ConversionBarLoader $conversionBarLoader)
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
        $barResult = $this->conversionBarLoader->loadForSalesChannel(
            $event->getContext(),
            $event->getSalesChannelContext());

        if ($barResult->count()) {
            $event->getPage()->addExtension('nme_conversion_bar', $barResult);
        }
    }
}
