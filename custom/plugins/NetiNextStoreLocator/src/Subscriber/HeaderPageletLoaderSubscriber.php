<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Subscriber;

use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HeaderPageletLoaderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly PluginConfigStruct        $pluginConfig,
        private readonly EntityRepository $categoryRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'onLoaded',
        ];
    }

    public function onLoaded(HeaderPageletLoadedEvent $event): void
    {
        $page    = $event->getPagelet();
        $request = $event->getRequest();

        /** @var string|null $route */
        $route   = $request->attributes->get('_route');

        if (
            $route === 'frontend.store_locator.index'
            && $categoryId = $this->pluginConfig->getBreadcrumbCategory()
        ) {
            $criteria = new Criteria([ $categoryId ]);
            $result   = $this->categoryRepository->search($criteria, $event->getContext());

            /** @var CategoryEntity|null $category */
            $category = $result->first();

            if ($category instanceof CategoryEntity) {
                $page->getNavigation()?->setActive($category);
            }
        }
    }
}
