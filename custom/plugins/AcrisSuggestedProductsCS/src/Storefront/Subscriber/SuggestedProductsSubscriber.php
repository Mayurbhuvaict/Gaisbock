<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Storefront\Subscriber;

use Acris\SuggestedProducts\Components\CustomersAlsoBoughtStruct;
use Acris\SuggestedProducts\Components\CustomersAlsoViewedStruct;
use Acris\SuggestedProducts\Components\Events\SuggestedProductsListingResultEvent;
use Acris\SuggestedProducts\Components\TabOrderStruct;
use Acris\SuggestedProducts\Framework\Cookie\CustomersAlsoViewedCookieProvider;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingLoader;
use Shopware\Core\Content\Product\SalesChannel\ProductAvailableFilter;
use Shopware\Core\Content\ProductStream\Service\ProductStreamBuilderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SuggestedProductsSubscriber implements EventSubscriberInterface
{

    public const TABNAME_PRODUCTS_SAME_CATEOGORY = 'productsSameCategory';
    public const TABNAME_RECENTLY_VIEWED = 'recentlyViewed';
    public const TABNAME_CUSTOMERS_ALSO_BOUGHT = 'customersAlsoBought';
    public const TABNAME_CUSTOMERS_ALSO_VIEWED = 'customersAlsoViewed';

    private ProductStreamBuilderInterface $productStreamBuilder;

    private ProductListingLoader $productListingLoader;

    private EventDispatcherInterface $eventDispatcher;

    private SystemConfigService $systemConfigService;

    private Connection $connection;

    private EntityRepository $customersAlsoViewedRepository;

    private EntityRepository $sortingRepository;

    public function __construct(
        ProductStreamBuilderInterface $productStreamBuilder,
        ProductListingLoader          $productListingLoader,
        EventDispatcherInterface      $eventDispatcher,
        SystemConfigService           $systemConfigService,
        Connection                    $connection,
        EntityRepository     $customersAlsoViewedRepository,
        EntityRepository     $sortingRepository
    )
    {
        $this->productStreamBuilder = $productStreamBuilder;
        $this->productListingLoader = $productListingLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->systemConfigService = $systemConfigService;
        $this->connection = $connection;
        $this->customersAlsoViewedRepository = $customersAlsoViewedRepository;
        $this->sortingRepository = $sortingRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'OnProductPageLoaded'
        ];
    }

    public function OnProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        if ($this->systemConfigService->get('AcrisSuggestedProductsCS.config.enableCustomersAlsoBought', $event->getSalesChannelContext()->getSalesChannelId())) {
            $this->setCustomersAlsoBought($event);
        }
        if ($this->systemConfigService->get('AcrisSuggestedProductsCS.config.enableCustomersAlsoViewed', $event->getSalesChannelContext()->getSalesChannelId())) {
            $this->setCustomersAlsoViewed($event);
        }
        if ($this->systemConfigService->get('AcrisSuggestedProductsCS.config.enableProductsSameCategory', $event->getSalesChannelContext()->getSalesChannelId())) {
            $this->setSuggestedProducts($event);
        }

        $this->setTabOrder($event);
    }

    private function setTabOrder(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();
        $context = $event->getSalesChannelContext();
        $tabsNamesList = [self::TABNAME_PRODUCTS_SAME_CATEOGORY, self::TABNAME_RECENTLY_VIEWED, self::TABNAME_CUSTOMERS_ALSO_BOUGHT, self::TABNAME_CUSTOMERS_ALSO_VIEWED];
        $tabsList = [];
        foreach ($tabsNamesList as $tabName) {
            $tabOrder = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.' . $tabName, $context->getSalesChannelId());
            if (!empty($tabOrder)) {
                $tabsList[$tabName] = $tabOrder;
            } else {
                $tabsList[$tabName] = null;
            }
        }
        asort($tabsList, \SORT_REGULAR);
        $product->addExtension(TabOrderStruct::EXTENSION_KEY, new TabOrderStruct($tabsList));
    }

    private function setSuggestedProducts(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();
        $context = $event->getSalesChannelContext();

        $category = $product->getSeoCategory();
        if (empty($category)) {
            if (!empty($product) && !empty($product->getCategories()) && $product->getCategories()->count() > 0) {
                $category = $product->getCategories()->first();
            } else {
                return;
            }
        }
        $criteria = $this->getCriteria($event, $category, $context);

        $productListingResult = $this->productListingLoader->load($criteria, $context);

        $event = new SuggestedProductsListingResultEvent($event->getRequest(), $productListingResult, $context);
        $this->eventDispatcher->dispatch(
            new SuggestedProductsListingResultEvent($event->getRequest(), $productListingResult, $context),
            $event->getName()
        );

        if ($productListingResult->getTotal() > 0 && $productListingResult->first()) {
            $productCollection = $productListingResult->getEntities();
            $product->addExtension('acrisSuggestedProducts', new ArrayEntity([
                'products' => $productCollection
            ]));
        }
    }

    private function getCriteria(ProductPageLoadedEvent $event, CategoryEntity $category, SalesChannelContext $context): Criteria
    {
        $criteria = new Criteria();
        $enableParentProduct = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.enableParentProductView', $context->getSalesChannel()->getId());
        if(!$enableParentProduct) {
            $criteria->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [
                new EqualsFilter('id', $event->getPage()->getProduct()->getId()),
            ]));
        }

        $limit = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.maximumNumberOfProducts', $context->getSalesChannel()->getId());
        if (empty($limit)) $limit = 12;

        $criteria->setLimit($limit);

        if ($category->getProductAssignmentType() === CategoryDefinition::PRODUCT_ASSIGNMENT_TYPE_PRODUCT_STREAM && $category->getProductStreamId() !== null) {
            $filters = $this->productStreamBuilder->buildFilters(
                $category->getProductStreamId(),
                $context->getContext()
            );

            $criteria->addFilter(...$filters);
        } else {
            $criteria->addFilter(
                new EqualsFilter('product.categoriesRo.id', $category->getId())
            );
        }

        $defaultSorting = $this->systemConfigService->get('core.listing.defaultSorting', $context->getSalesChannel()->getId());
        if (!empty($defaultSorting)) {
            $sortingCriteria = new Criteria();
            $sortingCriteria->addFilter(new EqualsFilter('key', $defaultSorting));
            $sortingResult = $this->sortingRepository->search($sortingCriteria, $context->getContext())->first();
            if (!empty($sortingResult)) {
                $fields = $sortingResult->getFields();
                if (!empty($fields)) {
                    $field = $fields[0]['field'];
                    $order = $fields[0]['order'];
                    $sorting = new FieldSorting($field, $order);
                    $criteria->addSorting($sorting);
                }
            }
        }

        $criteria->addFilter(
            new ProductAvailableFilter($context->getSalesChannel()->getId(), ProductVisibilityDefinition::VISIBILITY_ALL)
        );

        if($this->systemConfigService->get('AcrisSuggestedProductsCS.config.loadMoreCrossSellingMedia', $context->getSalesChannelId())){
            $criteria->addAssociation('media');
            $criteria->getAssociation('media')->setLimit(2);
        }

        return $criteria;
    }

    private function setCustomersAlsoBought(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();
        $context = $event->getSalesChannelContext();

        $customersAlsoBoughtReadLimit = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.customersAlsoBoughtReadLimit', $context->getSalesChannelId());
        if (empty($customersAlsoBoughtReadLimit)) {
            $customersAlsoBoughtReadLimit = 7;
        }

        $customersAlsoBoughtReadDays = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.customersAlsoBoughtReadDays', $context->getSalesChannelId());

        if (empty($customersAlsoBoughtReadDays)) {
            $customersAlsoBoughtReadDays = 365;
        }

        $productId = $product->getId();

        $orderIdsStmt = $this->connection->prepare('SELECT LOWER(HEX(order_id)) as order_id FROM order_line_item WHERE product_id = ? and version_id = ? and DATEDIFF(current_date, created_at) between 0 and ?');
        $orderIdsStmt->bindValue(1, Uuid::fromHexToBytes($productId));
        $orderIdsStmt->bindValue(2, Uuid::fromHexToBytes($context->getVersionId()));
        $orderIdsStmt->bindValue(3, $customersAlsoBoughtReadDays, \PDO::PARAM_INT);
        $resultSet = $orderIdsStmt->executeQuery();
        $result = $resultSet->fetchAllAssociative();


        $orderIds = [];
        foreach ($result as $item) {
            $orderIds[] = $item['order_id'];
        }

        if (!empty($orderIds)) {
            $sql = '
            SELECT LOWER(HEX(product_id)) as product_id, count(product_id) as productIdCount FROM order_line_item WHERE order_id IN (:orderIds) and version_id = :versionId  GROUP BY product_id ORDER BY productIdCount DESC LIMIT :limit
            ';
            $rows = $this->connection->fetchAllAssociative(
                $sql,
                [
                    'orderIds' => Uuid::fromHexToBytesList($orderIds),
                    'versionId' => Uuid::fromHexToBytes($context->getVersionId()),
                    'limit' => $customersAlsoBoughtReadLimit
                ],
                [
                    'orderIds' => Connection::PARAM_STR_ARRAY,
                    'limit' => \PDO::PARAM_INT
                ]
            );

            $productIds = [];
            foreach ($rows as $row) {
                if (!empty($row['product_id']) && $row['product_id'] != $productId) {
                    $productIds[] = $row['product_id'];
                }
            }

            if (!empty($productIds)) {
                $criteria = new Criteria();
                $criteria->setIds($productIds);
                $criteria->addFilter(
                    new ProductAvailableFilter($context->getSalesChannel()->getId(), ProductVisibilityDefinition::VISIBILITY_ALL)
                );
                if($this->systemConfigService->get('AcrisSuggestedProductsCS.config.loadMoreCrossSellingMedia', $context->getSalesChannelId())){
                    $criteria->addAssociation('media');
                    $criteria->getAssociation('media')->setLimit(2);
                }
                try {
                    $customersAlsoBoughtProducts = $this->productListingLoader->load($criteria, $context)->getEntities()->getElements();
                } catch (\Exception $e) {
                    $customersAlsoBoughtProducts = [];
                }
                $product->addExtension(CustomersAlsoBoughtStruct::EXTENSION_KEY, new CustomersAlsoBoughtStruct($customersAlsoBoughtProducts));
            }
        }
    }

    private function setCustomersAlsoViewed(ProductPageLoadedEvent $event)
    {
        $product = $event->getPage()->getProduct();
        $context = $event->getSalesChannelContext();
        $cookie = $event->getRequest()->cookies->get(CustomersAlsoViewedCookieProvider::COOKIE_ID);

        $customersAlsoViewedReadLimit = $this->systemConfigService->get('AcrisSuggestedProductsCS.config.customersAlsoViewedReadLimit', $context->getSalesChannelId());
        if (empty($customersAlsoViewedReadLimit)) {
            $customersAlsoViewedReadLimit = 7;
        }

        if (!empty($cookie) && Uuid::isValid($cookie)) {
            $productId = $cookie;
            if ($product->getId() !== $productId) {
                $viewedProductId = $product->getId();
                $sessionId = $event->getRequest()->getSession()->getId();
                $salesChannelId = $context->getSalesChannelId();
                $hash = \hash('md5', $productId . $viewedProductId . $sessionId . $salesChannelId, false);
                try {
                    $this->customersAlsoViewedRepository->upsert(
                        [[
                            'id' => Uuid::randomHex(),
                            'productId' => $productId,
                            'viewedProductId' => $viewedProductId,
                            'sessionId' => $sessionId,
                            'salesChannelId' => $salesChannelId,
                            'rowHash' => $hash
                        ]], $context->getContext()
                    );
                } catch (\Exception $exception) {

                }
            }
        }

        $productId = $product->getId();

        $sql = '
            SELECT LOWER(HEX(viewed_product_id)) as viewed_product_id
            FROM acris_customers_also_viewed WHERE product_id = :productId and sales_channel_id = :salesChannelId and session_id != :sessionId
            GROUP BY viewed_product_id ORDER BY count(viewed_product_id) DESC LIMIT :limit
            ';
        $rows = $this->connection->fetchAllAssociative(
            $sql,
            [
                'productId' => Uuid::fromHexToBytes($productId),
                'salesChannelId' => Uuid::fromHexToBytes($context->getSalesChannelId()),
                'limit' => $customersAlsoViewedReadLimit,
                'sessionId' => $event->getRequest()->getSession()->getId(),
            ],
            [
                'limit' => \PDO::PARAM_INT
            ]
        );

        $productIds = [];
        foreach ($rows as $row) {
            if (!empty($row['viewed_product_id']) && $row['viewed_product_id'] != $product->getId()) {
                $productIds[] = $row['viewed_product_id'];
            }
        }

        if (!empty($productIds)) {
            $criteria = new Criteria();
            $criteria->setIds($productIds);
            $criteria->addFilter(
                new ProductAvailableFilter($context->getSalesChannel()->getId(), ProductVisibilityDefinition::VISIBILITY_ALL)
            );
            if($this->systemConfigService->get('AcrisSuggestedProductsCS.config.loadMoreCrossSellingMedia', $context->getSalesChannelId())){
                $criteria->addAssociation('media');
                $criteria->getAssociation('media')->setLimit(2);
            }
            try {
                $customersAlsoViewedProducts = $this->productListingLoader->load($criteria, $context)->getEntities()->getElements();
            } catch (\Exception $e) {
                $customersAlsoViewedProducts = [];
            }
            $product->addExtension(CustomersAlsoViewedStruct::EXTENSION_KEY, new CustomersAlsoViewedStruct($customersAlsoViewedProducts));
        }
    }
}
