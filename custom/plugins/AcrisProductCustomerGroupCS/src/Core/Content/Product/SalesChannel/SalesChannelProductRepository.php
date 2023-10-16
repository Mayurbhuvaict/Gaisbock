<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Content\Product\SalesChannel;

use Acris\ProductCustomerGroup\Components\BlockProductService;
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

    private BlockProductService $blockProductService;

    private SystemConfigService $configService;

    public function __construct(
        SalesChannelRepository $parent,
        BlockProductService $blockProductService,
        SystemConfigService $configService
    ) {
        $this->parent = $parent;
        $this->blockProductService = $blockProductService;
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
        $blockedProductIds = $this->blockProductService->getBlockedProductIdsForCustomerGroupId($salesChannelContext->getCurrentCustomerGroup()->getId(), $salesChannelContext->getContext());
        $filters = [];

        if($this->configService->get('AcrisProductCustomerGroupCS.config.blockProductsIfNoCustomerGroupAssigned', $salesChannelContext->getSalesChannel()->getId()) === BlockProductService::DEFAULT_PLUGIN_CONFIG_BLOCK_PRODUCT_IF_NO_CUSTOMER_GROUPS_ASSIGNED) {
            $criteria->addAssociation('product.acrisBlockCustomerGroup');

            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [
                new EqualsFilter('product.acrisBlockCustomerGroup.id', null)
            ]);
        }

        if (!empty($blockedProductIds)) {
            $filters[] = new NotFilter(NotFilter::CONNECTION_AND, [new EqualsAnyFilter('id', $blockedProductIds)]);
        }

        if (!empty($filters)) {
            $criteria->addFilter(new MultiFilter(MultiFilter::CONNECTION_OR, $filters));
        }
    }
}
