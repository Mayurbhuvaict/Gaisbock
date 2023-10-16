<?php declare(strict_types=1);

namespace Acris\CategoryCustomerGroup\Storefront\Subscriber;

use Acris\CategoryCustomerGroup\Components\BlockCategoryService;
use Shopware\Core\Content\Product\Events\ProductSearchCriteriaEvent;
use Shopware\Core\Content\Product\Events\ProductSuggestCriteriaEvent;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductSubscriber implements EventSubscriberInterface
{
    public const HIDE_SEARCH_AND_SUGGEST = 'acrisCategoryCustomerGroupSearchAndSuggest';
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
            ProductPageLoadedEvent::class => 'onProductLoaded',
            ProductPageCriteriaEvent::class => 'onProductLoaderCriteria',
            ProductSuggestCriteriaEvent::class => 'onProductSuggestCriteria',
            ProductSearchCriteriaEvent::class => 'onProductSearchCriteria'
        ];
    }

    public function onProductLoaderCriteria(ProductPageCriteriaEvent $event): void
    {
        $event->getCriteria()->addAssociation('categories');
    }

    public function onProductSuggestCriteria(ProductSuggestCriteriaEvent $event): void
    {
        $event->getSalesChannelContext()->addExtension(self::HIDE_SEARCH_AND_SUGGEST, new ArrayEntity([]));
    }

    public function onProductSearchCriteria(ProductSearchCriteriaEvent $event): void
    {
        $event->getSalesChannelContext()->addExtension(self::HIDE_SEARCH_AND_SUGGEST, new ArrayEntity([]));
    }

    public function onProductLoaded(ProductPageLoadedEvent $event): void
    {
        if(!$this->configService->get('AcrisCategoryCustomerGroupCS.config.hideAssignedProductsForOtherCategories', $event->getSalesChannelContext()->getSalesChannel()->getId())) {
            return;
        }

        $product = $event->getPage()->getProduct();
        if(empty($product)) {
            return;
        }

        if (!empty($product->getCategories()) && $product->getCategories()->count() > 0) {
            foreach ($product->getCategories()->getElements() as $key => $category) {
                if($this->blockCategoryService->isCategoryBlockedForCustomerGroup($key, $event->getSalesChannelContext()) === true) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }
}
