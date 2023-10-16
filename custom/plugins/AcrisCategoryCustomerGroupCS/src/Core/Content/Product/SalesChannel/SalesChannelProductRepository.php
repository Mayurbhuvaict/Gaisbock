<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Core\Content\Product\SalesChannel;

use Acris\CategoryCustomerGroup\Components\BlockCategoryService;
use Acris\CategoryCustomerGroup\Storefront\Subscriber\ProductSubscriber;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class SalesChannelProductRepository extends SalesChannelRepository
{
    private SalesChannelRepository $parent;

    private BlockCategoryService $blockCategoryService;

    private SystemConfigService $configService;

    public function __construct(
        SalesChannelRepository $parent,
        BlockCategoryService $blockCategoryService,
        SystemConfigService $configService
    ) {
        $this->parent = $parent;
        $this->blockCategoryService = $blockCategoryService;
        $this->configService = $configService;
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function search(Criteria $criteria, SalesChannelContext $salesChannelContext): EntitySearchResult
    {
        $this->blockProductsForCustomerGroup($criteria, $salesChannelContext);

        return $this->parent->search($criteria, $salesChannelContext);
    }

    public function aggregate(Criteria $criteria, SalesChannelContext $salesChannelContext): AggregationResultCollection
    {
        return $this->parent->aggregate($criteria, $salesChannelContext);
    }

    public function searchIds(Criteria $criteria, SalesChannelContext $salesChannelContext): IdSearchResult
    {
        $this->blockProductsForCustomerGroup($criteria, $salesChannelContext);

        return $this->parent->searchIds($criteria, $salesChannelContext);
    }

    private function blockProductsForCustomerGroup(Criteria $criteria, SalesChannelContext $salesChannelContext): void
    {
        if (!$this->hideProducts($salesChannelContext)) return;

        $blockedCategoryIds = $this->blockCategoryService->getBlockedCategoryIdsForCustomerGroupId($salesChannelContext->getCurrentCustomerGroup()->getId(), $salesChannelContext->getContext());
        $filters = [];

        if($this->configService->get('AcrisCategoryCustomerGroupCS.config.blockCategoriesIfNoCustomerGroupAssigned', $salesChannelContext->getSalesChannel()->getId()) === BlockCategoryService::DEFAULT_PLUGIN_CONFIG_BLOCK_CATEGORY_IF_NO_CUSTOMER_GROUPS_ASSIGNED) {
            $criteria->addAssociation('categories.customerGroup');

            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [
                new EqualsFilter('product.categories.customerGroup.id', null)
            ]);
        }

        if (!empty($blockedCategoryIds)) {
            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [new EqualsAnyFilter('product.categoriesRo.id', $blockedCategoryIds)]);
        }

        if (!empty($filters)) {
            $criteria->addFilter(new MultiFilter(MultiFilter::CONNECTION_OR, $filters));
        }
    }

    private function hideProducts(SalesChannelContext $salesChannelContext): bool
    {
        if ($salesChannelContext->hasExtension(ProductSubscriber::HIDE_SEARCH_AND_SUGGEST)) {
            return $this->configService->get('AcrisCategoryCustomerGroupCS.config.hideAssignedProductsForSearch', $salesChannelContext->getSalesChannel()->getId());
        }

        return $this->configService->get('AcrisCategoryCustomerGroupCS.config.hideAssignedProductsForOtherCategories', $salesChannelContext->getSalesChannel()->getId());
    }
}
