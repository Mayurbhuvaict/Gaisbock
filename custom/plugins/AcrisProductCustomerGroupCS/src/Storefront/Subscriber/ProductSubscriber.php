<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Storefront\Subscriber;

use Acris\ProductCustomerGroup\Components\BlockProductService;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var BlockProductService
     */
    private $blockProductService;

    /**
     * @var SystemConfigService
     */
    private $configService;

    public function __construct(BlockProductService $blockProductService, SystemConfigService $configService)
    {
        $this->blockProductService = $blockProductService;
        $this->configService = $configService;
    }

    public static function getSubscribedEvents(): array
    {
        return[
            ProductPageLoadedEvent::class => 'onProductLoaded'
        ];
    }

    public function onProductLoaded(ProductPageLoadedEvent $event)
    {
        $productId = $event->getPage()->getProduct()->getId();
        if(empty($productId)) {
            return;
        }

        if($this->configService->get('AcrisProductCustomerGroupCS.config.blockProductsIfNoCustomerGroupAssigned', $event->getSalesChannelContext()->getSalesChannel()->getId()) === BlockProductService::DEFAULT_PLUGIN_CONFIG_BLOCK_PRODUCT_IF_NO_CUSTOMER_GROUPS_ASSIGNED && $this->blockProductService->checkIfNoCustomerGroupsAssigned($productId, $event->getSalesChannelContext()->getContext())) {
            throw new NotFoundHttpException();
        }

        $blockedProductIds = $this->blockProductService->getBlockedProductIdsForCustomerGroupId($event->getSalesChannelContext()->getCurrentCustomerGroup()->getId(), $event->getContext());
        if(in_array($productId, $blockedProductIds)) {
            throw new NotFoundHttpException();
        }
    }
}
