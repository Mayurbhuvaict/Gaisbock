<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Core\Content\Product\Cart;

use Acris\ProductCustomerGroup\Components\BlockProductService;
use Acris\ProductCustomerGroup\Components\Validation\ProductBlockedForCustomerGroupError;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class AcrisProductCustomerGroupCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    private BlockProductService $productBlockService;
    private SystemConfigService $configService;

    public function __construct(
        BlockProductService $productBlockService,
        SystemConfigService $configService
    ) {
        $this->productBlockService = $productBlockService;
        $this->configService = $configService;
    }

    public function collect(
        CartDataCollection $data,
        Cart $original,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $lineItems = $original
            ->getLineItems()
            ->filterFlatByType(LineItem::PRODUCT_LINE_ITEM_TYPE);

        $blockedProductIds = $this->productBlockService->getBlockedProductIdsForCustomerGroupId($context->getCurrentCustomerGroup()->getId(), $context->getContext());

        foreach ($lineItems as $lineItem) {
            $id = $lineItem->getReferencedId();
            $key = 'product-' . $id;
            $product = $data->get($key);

            if ($product instanceof SalesChannelProductEntity) {
                if ((!empty($blockedProductIds) && is_array($blockedProductIds) && in_array($product->getId(), $blockedProductIds)) || (empty($blockedProductIds) && $this->configService->get('AcrisProductCustomerGroupCS.config.blockProductsIfNoCustomerGroupAssigned', $context->getSalesChannel()->getId()) === BlockProductService::DEFAULT_PLUGIN_CONFIG_BLOCK_PRODUCT_IF_NO_CUSTOMER_GROUPS_ASSIGNED && $this->productBlockService->checkIfNoCustomerGroupsAssigned($id, $context->getContext()))) {
                    $original->getLineItems()->remove($lineItem->getId());
                    $original->addErrors(new ProductBlockedForCustomerGroupError((string) $product->getTranslation('name')));
                }
            }
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        if (!empty($original->getErrors()) && $original->getErrors()->count() > 0) {
            foreach ($original->getErrors()->getElements() as $error) {
                if ($error instanceof ProductBlockedForCustomerGroupError) {
                    $toCalculate->addErrors($error);
                }
            }
        }
    }
}
