<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Content\Product\SalesChannel\Detail;

use Acris\ProductCustomerGroup\Components\BlockProductService;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\PropertyGroupCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Content\Product\SalesChannel\Detail\ProductConfiguratorLoader as ParentClass;

class ProductConfiguratorLoader extends ParentClass
{
    private ParentClass $parent;

    private BlockProductService $productBlockService;

    public function __construct(
        ParentClass $parent,
        BlockProductService $productBlockService
    ) {
        $this->parent = $parent;
        $this->productBlockService = $productBlockService;
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function load(
        SalesChannelProductEntity $product,
        SalesChannelContext $context
    ): PropertyGroupCollection {
        $groups = $this->parent->load($product, $context);

        $productId = !empty($product->getParentId()) ? $product->getParentId() : $product->getId();
        $options = !empty($product->getOptionIds()) ? $product->getOptionIds() : [];

        $this->productBlockService->blockProductOptionsOfBlockedVariants($options, $groups, $context->getCurrentCustomerGroup()->getId(), $productId, $context);

        return $groups;
    }
}
