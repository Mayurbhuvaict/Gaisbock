<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Storefront\Subscriber;

use Acris\CategoryCustomerGroup\Components\BlockCategoryService;
use Shopware\Core\Content\Category\Event\NavigationLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategorySubscriber implements EventSubscriberInterface
{
    public const ACRIS_SEARCH_CATEGORY_SEARCH_CRITERIA_EVENT = 'Acris\Search\Components\ContentSearch\Events\CategorySearchCriteriaEvent';

    /**
     * @var BlockCategoryService
     */
    private $blockCategoryService;

    /**
     * @var SystemConfigService
     */
    private $configService;

    public function __construct(BlockCategoryService $blockCategoryService, SystemConfigService $configService)
    {
        $this->blockCategoryService = $blockCategoryService;
        $this->configService = $configService;
    }

    public static function getSubscribedEvents(): array
    {
        return[
            NavigationPageLoadedEvent::class => 'onNavigationPageLoaded',
            NavigationLoadedEvent::class => 'onNavigationLoaded',
            self::ACRIS_SEARCH_CATEGORY_SEARCH_CRITERIA_EVENT => 'onCategorySearchCriteria'
        ];
    }

    public function onNavigationPageLoaded(NavigationPageLoadedEvent $event): void
    {
        $navigationId = $event->getRequest()->get('navigationId', $event->getSalesChannelContext()->getSalesChannel()->getNavigationCategoryId());
        if(empty($navigationId)) {
            return;
        }

        if($this->blockCategoryService->isCategoryBlockedForCustomerGroup($navigationId, $event->getSalesChannelContext()) === true) {
            throw new NotFoundHttpException();
        }
    }

    public function onNavigationLoaded(NavigationLoadedEvent $event): void
    {
        $navigation = $event->getNavigation();

        $convertedTreeItems = [];
        foreach ($navigation->getTree() as $key => $treeItem) {
            if($this->blockCategoryService->blockCategory($treeItem, $event->getSalesChannelContext()) === false) {
                continue;
            }
            $convertedTreeItems[$key] = $treeItem;
        }

        $navigation->setTree($convertedTreeItems);
    }

    public function onCategorySearchCriteria($event): void
    {
        $blockedCategoryIds = $this->blockCategoryService->getBlockedCategoryIdsForCustomerGroupId($event->getSalesChannelContext()->getCurrentCustomerGroup()->getId(), $event->getContext());

        $filters = [];

        if($this->configService->get('AcrisCategoryCustomerGroupCS.config.blockCategoriesIfNoCustomerGroupAssigned', $event->getSalesChannelContext()->getSalesChannel()->getId()) === BlockCategoryService::DEFAULT_PLUGIN_CONFIG_BLOCK_CATEGORY_IF_NO_CUSTOMER_GROUPS_ASSIGNED) {
            $event->getCriteria()->addAssociation('customerGroup');

            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [
                new EqualsFilter('customerGroup.id', null)
            ]);
        }

        if (!empty($blockedCategoryIds)) {
            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [new EqualsAnyFilter('id', $blockedCategoryIds)]);
        }

        if (!empty($filters)) {
            $event->getCriteria()->addFilter(new MultiFilter(MultiFilter::CONNECTION_AND, $filters));
        }
    }
}
