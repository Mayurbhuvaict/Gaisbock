<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Components;

use Shopware\Core\Content\Category\Tree\TreeItem;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class BlockCategoryService
{
    public const DEFAULT_PLUGIN_CONFIG_BLOCK_CATEGORY_IF_NO_CUSTOMER_GROUPS_ASSIGNED = 'blockIfEmpty';

    /**
     * @var EntityRepository
     */
    private $categoryCustomerGroupRepository;

    /**
     * @var array
     */
    private $blockedCategoryIds;

    /**
     * @var array
     */
    private $categoryIdsAnyOtherCustomerGroupAssigned;

    /**
     * @var null|bool
     */
    private $checkNoCustomerGroupAssigned;

    /**
     * @var SystemConfigService
     */
    private $configService;

    public function __construct(EntityRepository $categoryCustomerGroupRepository, SystemConfigService $configService)
    {
        $this->categoryCustomerGroupRepository = $categoryCustomerGroupRepository;
        $this->blockedCategoryIds = [];
        $this->categoryIdsAnyOtherCustomerGroupAssigned = [];
        $this->checkNoCustomerGroupAssigned = null;
        $this->configService = $configService;
    }

    public function isCategoryBlockedForCustomerGroup(string $categoryId, SalesChannelContext $salesChannelContext)
    {
        $customerGroupId = $salesChannelContext->getCurrentCustomerGroup()->getId();
        $blockedCategoryIds = $this->getBlockedCategoryIdsForCustomerGroupId($customerGroupId, $salesChannelContext->getContext());

        $checkNoCustomerGroupAssigned = $this->isCheckNoCustomerGroupAssigned($salesChannelContext->getSalesChannel()->getId());
        $categoryIdsAnyOtherCustomerGroup = [];
        if($checkNoCustomerGroupAssigned === true) {
            $categoryIdsAnyOtherCustomerGroup = $this->getCategoryIdsAnyOtherCustomerGroupAssigned($customerGroupId, $salesChannelContext->getContext());
        }

        return in_array($categoryId, $blockedCategoryIds) === true
            || ($checkNoCustomerGroupAssigned === true && in_array($categoryId, $categoryIdsAnyOtherCustomerGroup) === false);
    }

    public function getBlockedCategoryIdsForCustomerGroupId(?string $customerGroupId, Context $context): array
    {
        if(empty($customerGroupId)) {
            return [];
        }

        if(array_key_exists($customerGroupId, $this->blockedCategoryIds) && $this->blockedCategoryIds[$customerGroupId]) {
            return $this->blockedCategoryIds[$customerGroupId];
        }

        /** @var IdSearchResult $categoryIdSearch */
        $blockedCategoryIdsResult = $this->categoryCustomerGroupRepository->searchIds((new Criteria())->addFilter(new EqualsFilter('customerGroupId', $customerGroupId)), $context);

        if(empty($blockedCategoryIdsResult->getIds())) {
            return [];
        }

        $blockedCategoryIds = [];
        foreach ($blockedCategoryIdsResult->getIds() as $customerGroupCategory) {
            array_push($blockedCategoryIds, $customerGroupCategory['categoryId']);
        }

        $this->blockedCategoryIds[$customerGroupId] = $blockedCategoryIds;
        return $blockedCategoryIds;
    }

    public function getCategoryIdsAnyOtherCustomerGroupAssigned(?string $customerGroupId, Context $context): array
    {
        if(empty($customerGroupId)) {
            return [];
        }

        if(array_key_exists($customerGroupId, $this->categoryIdsAnyOtherCustomerGroupAssigned) && $this->categoryIdsAnyOtherCustomerGroupAssigned[$customerGroupId]) {
            return $this->categoryIdsAnyOtherCustomerGroupAssigned[$customerGroupId];
        }

        /** @var IdSearchResult $categoryIdsAnyOtherCustomerGroupResult */
        $categoryIdsAnyOtherCustomerGroupResult = $this->categoryCustomerGroupRepository->searchIds((new Criteria())->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [new EqualsFilter('customerGroupId', $customerGroupId)])), $context);

        if(empty($categoryIdsAnyOtherCustomerGroupResult->getIds())) {
            return [];
        }

        $categoryIdsAnyOtherCustomerGroup = [];
        foreach ($categoryIdsAnyOtherCustomerGroupResult->getIds() as $customerGroupCategory) {
            array_push($categoryIdsAnyOtherCustomerGroup, $customerGroupCategory['categoryId']);
        }

        $this->categoryIdsAnyOtherCustomerGroupAssigned[$customerGroupId] = $categoryIdsAnyOtherCustomerGroup;
        return $categoryIdsAnyOtherCustomerGroup;
    }

    public function blockCategory(TreeItem $treeItem, SalesChannelContext $salesChannelContext)
    {
        if($this->isCategoryBlockedForCustomerGroup($treeItem->getCategory()->getId(), $salesChannelContext) === true) {
            return false;
        }
        $convertedTreeItems = [];

        foreach ($treeItem->getChildren() as $key => $childTreeItem) {
            $childTreeItem = $this->blockCategory($childTreeItem, $salesChannelContext);
            if($childTreeItem === false) {
                continue;
            }
            array_push($convertedTreeItems, $childTreeItem);
        }
        $treeItem->setChildren($convertedTreeItems);
        return $treeItem;
    }

    private function isCheckNoCustomerGroupAssigned(string $salesChannelId): bool
    {
        if(is_bool($this->checkNoCustomerGroupAssigned)) {
            return $this->checkNoCustomerGroupAssigned;
        }
        return $this->configService->get('AcrisCategoryCustomerGroupCS.config.blockCategoriesIfNoCustomerGroupAssigned', $salesChannelId) === BlockCategoryService::DEFAULT_PLUGIN_CONFIG_BLOCK_CATEGORY_IF_NO_CUSTOMER_GROUPS_ASSIGNED;
    }
}
