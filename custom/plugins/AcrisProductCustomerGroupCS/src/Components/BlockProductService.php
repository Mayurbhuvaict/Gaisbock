<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Components;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity;
use Shopware\Core\Content\Property\PropertyGroupCollection;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class BlockProductService
{
    public const DEFAULT_PLUGIN_CONFIG_BLOCK_PRODUCT_IF_NO_CUSTOMER_GROUPS_ASSIGNED = 'blockIfEmpty';

    /**
     * @var EntityRepository
     */
    private $productCustomerGroupRepository;

    /**
     * @var EntityRepository
     */
    private $productRepository;

    /**
     * @var array
     */
    private $blockProductIds;

    public function __construct
    (
        EntityRepository $productCustomerGroupRepository,
        EntityRepository $productRepository
    )
    {
        $this->productCustomerGroupRepository = $productCustomerGroupRepository;
        $this->productRepository = $productRepository;
        $this->blockProductIds = [];
    }

    public function getBlockedProductIdsForCustomerGroupId(?string $customerGroupId, Context $context): array
    {
        if(empty($customerGroupId)) {
            return [];
        }

        if(array_key_exists($customerGroupId, $this->blockProductIds) && $this->blockProductIds[$customerGroupId]) {
            return $this->blockProductIds[$customerGroupId];
        }

        /** @var IdSearchResult $blockProductIdsResult */
        $blockProductIdsResult = $this->productCustomerGroupRepository->searchIds((new Criteria())->addFilter(new EqualsFilter('customerGroupId', $customerGroupId)), $context);

        if(empty($blockProductIdsResult->getIds())) {
            return [];
        }

        $blockProductIds = [];
        foreach ($blockProductIdsResult->getIds() as $customerGroupProduct) {
            array_push($blockProductIds, $customerGroupProduct['productId']);
        }

        $blockProductIds = $this->getVariantIds($blockProductIds, $context);
        $this->blockProductIds[$customerGroupId] = $blockProductIds;
        return $blockProductIds;
    }

    public function checkIfNoCustomerGroupsAssigned(?string $productId, Context $context): bool
    {
        if(empty($productId)) {
            return false;
        }

        /** @var IdSearchResult $productIdSearch */
        $productIdSearch = $this->productCustomerGroupRepository->searchIds((new Criteria())->addFilter(new EqualsFilter('productId', $productId)), $context);

        return $productIdSearch->getTotal() === 0;
    }

    public function getVariantIds(array $productIds, Context $context): array
    {
        if (empty($productIds)) return [];
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('parentId', $productIds));
        /** @var IdSearchResult $variantIds */
        $variantIds = $this->productRepository->searchIds($criteria, $context);
        if ($variantIds->getTotal() <= 0) return $productIds;
        foreach ($variantIds->getIds() as $productId) {
            array_push($productIds, $productId);
        }

        return $productIds;
    }

    public function blockProductOptionsOfBlockedVariants(array $optionIds, PropertyGroupCollection $groups, string $customerGroupId, string $productId, SalesChannelContext $context): void
    {
        $blockedProductIds = $this->getBlockedProductIdsForCustomerGroupId($customerGroupId, $context->getContext());
        if (empty($blockedProductIds)) return;

        $variantResults = $this->productRepository->search($this->variantCriteria($productId, $blockedProductIds), $context->getContext());
        if (!empty($variantResults) && $variantResults->count() > 0) {
            /** @var ProductEntity $variant */
            foreach ($variantResults->getEntities()->getElements() as $variant) {
                $variantOptionIds = $variant->getOptionIds();
                if (!empty($variantOptionIds)) {
                    $diffOptionIds = array_diff($variantOptionIds, $optionIds);
                    if (count($diffOptionIds) === 1) {
                        foreach ($diffOptionIds as $optionId) {
                            if (!empty($groups) && $groups->count() > 0) {
                                /** @var PropertyGroupEntity $group */
                                foreach ($groups as $group) {
                                    $options = $group->getOptions();
                                    if (!empty($options) && $options->count() > 0) {
                                        /** @var PropertyGroupOptionEntity $option */
                                        foreach ($options as $option) {
                                            if ($optionId === $option->getId()) {
                                                $options->remove($option->getId());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function variantCriteria(string $productId, array $blockedProductIds) : Criteria
    {
        $criteria = new Criteria();
        $criteria->addAssociation('options');
        $criteria->addFilter(new MultiFilter(MultiFilter::CONNECTION_AND, [
            new EqualsFilter('parentId', $productId),
            new EqualsAnyFilter('id', $blockedProductIds),
        ]));

        return $criteria;
    }
}
