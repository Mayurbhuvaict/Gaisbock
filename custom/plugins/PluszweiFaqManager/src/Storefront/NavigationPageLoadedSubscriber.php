<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Storefront;


use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NavigationPageLoadedSubscriber implements EventSubscriberInterface
{
    const LIMIT = 6;

    public function __construct(private readonly FaqLoader $faqLoader)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            NavigationPageLoadedEvent::class => [
                ['onNavigationPageLoaded'],
            ]
        ];
    }

    public function onNavigationPageLoaded(NavigationPageLoadedEvent $event)
    {
        $page = $event->getPage();
        $header = $page->getHeader();
        if (!$header || !$header->getNavigation()) {
            return;
        }

        $category = $header->getNavigation()->getActive();
        if (!$category || $category->getType() !== 'faq') {
            return;
        }
        $salesChannelContext = $event->getSalesChannelContext();
        $categories = $this->faqLoader->activeCategories($salesChannelContext);
        $featuredArticles = $this->faqLoader->allFeaturedArticles(self::LIMIT, $salesChannelContext);

        $page->addExtension('faqCategories', $categories);
        $page->addExtension('faqFeaturedArticles', $featuredArticles);
    }
}