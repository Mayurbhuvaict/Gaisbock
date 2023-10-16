<?php

namespace ProxaBasketCrossSellingSW6\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\SalesChannel\ProductCloseoutFilter;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\ProductStream\ProductStreamEntity;
use Shopware\Core\Content\ProductStream\Service\ProductStreamBuilderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class GetSliderProductsService
 * @package ProxaBasketCrossSellingSW6\Bundle\SliderProducts\Service
 */
class GetSliderProductsService
{
    /**
     * @var ProductStreamBuilderInterface
     */
    private $productStreamBuilder;

    /**
     * @var SalesChannelContext
     */
    private $salesChannelContext;

    /**
     * @var EntityRepository
     */
    private $crossSellingRepository;

    /**
     * @var EntityRepository
     */
    private $salesChannelProductRepository;

    /**
     * @var EntityRepository
     */
    private $productStreamRepository;

    /**
     * @var SystemConfigService
     */
    public $configService;

    /**
     * @var string
     */
    public $sliderType;

    /**
     * @var string
     */
    private $accessoriesGroupName;

    /**
     * @var string
     */
    private $similarArticlesGroupName;

    /**
     * @var string
     */
    private $viewType;

    /**
     * @var array
     */
    private $cartItemNumbers;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * GetSliderProductsService constructor.
     * @param SystemConfigService $configService
     * @param SalesChannelRepository $productRepository
     * @param EntityRepository $crossSellingRepository
     * @param EntityRepository $productStreamRepository
     * @param ProductStreamBuilderInterface $productStreamBuilder
     * @param Connection $connection
     */
    public function __construct(
        SystemConfigService           $configService,
        SalesChannelRepository        $productRepository,
        EntityRepository              $crossSellingRepository,
        EntityRepository              $productStreamRepository,
        ProductStreamBuilderInterface $productStreamBuilder,
        Connection                    $connection
    )
    {
        $this->configService = $configService;
        $this->salesChannelProductRepository = $productRepository;
        $this->crossSellingRepository = $crossSellingRepository;
        $this->productStreamRepository = $productStreamRepository;
        $this->productStreamBuilder = $productStreamBuilder;
        $this->connection = $connection;
    }

    /**
     * @param SalesChannelContext $salesChannelContext
     */
    public function setContext(SalesChannelContext $salesChannelContext)
    {
        $this->salesChannelContext = $salesChannelContext;
    }

    /**
     * @return array
     */
    public function getSliderProducts()
    {
        $pluginConfig = $this->configService->getDomain('ProxaBasketCrossSellingSW6');
        $this->accessoriesGroupName = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaAccessoriesProductGroupName'] ?? '';
        $this->similarArticlesGroupName = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaSimilarArticlesProductGroupName'] ?? '';
        $this->sliderType = 'horizontal';

        if ($this->viewType == 'ajax_cart') {
            $articleNumbersConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaArticlesAjaxCart'] ?? [];
            $accessoriesConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaShowOnlyAccessoriesAjaxCart'] ?? false;
            $similarConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaShowSimilarAjaxCart'] ?? false;
            $productStreamConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaProductStreamAjaxCart'] ?? [];
            $this->sliderType = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaAjaxSliderType'] ?? 'vertical';
        } elseif ($this->viewType == 'cart') {
            $articleNumbersConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaArticlesBasket'] ?? [];
            $accessoriesConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaShowOnlyAccessoriesBasket'] ?? false;
            $similarConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaShowSimilarBasket'] ?? false;
            $productStreamConfig = $pluginConfig['ProxaBasketCrossSellingSW6.config.proxaProductStreamBasket'] ?? [];
        }

        $resultProducts = [];
        if (!empty($articleNumbersConfig)) {
            foreach ($articleNumbersConfig as $articleNumber) {
                $articleAssoc = $this->getArticleAssocFromNumber($articleNumber);
                if ($articleAssoc) {
                    $resultProducts[] = $articleAssoc;
                }
            }
        } elseif ($this->cartItemNumbers) {
            $resultProducts = $this->getArticlesForOtherConfigs($accessoriesConfig, $similarConfig, $productStreamConfig);
        }

        return $resultProducts;
    }

    /**
     * @return string
     */
    public function getSliderTypeInAjaxCart(): string
    {
        return $this->sliderType;
    }

    /**
     * @param array $cartItemNumbers
     * @return void
     */
    public function setCartItemNumbers(array $cartItemNumbers)
    {
        $this->cartItemNumbers = $cartItemNumbers;
    }

    /**
     * @param string $viewType
     * @return void
     */
    public function setViewType(string $viewType)
    {
        $this->viewType = $viewType;
    }

    /**
     * @param bool $accessoriesConfig
     * @param bool $similarConfig
     * @param array $productStreamConfig
     * @return array
     */
    private function getArticlesForOtherConfigs(bool $accessoriesConfig, bool $similarConfig, array $productStreamConfig): array
    {
        $productStreamIDs = [];
        $sliderProducts = [];
        if ($productStreamConfig) {
            $productStreamIDs = $productStreamConfig;
        }

        $salesChannelContext = $this->salesChannelContext;

        if ($accessoriesConfig) {
//get accessories crossellings
            $sliderProducts = $this->getProducts($salesChannelContext, $this->accessoriesGroupName);
        } elseif ($similarConfig) {
//get similar articles crossellings
            $sliderProducts = $this->getProducts($salesChannelContext, $this->similarArticlesGroupName);
        } elseif ($productStreamIDs) {
//get crossellings by product group
            $stream = $this->getCrossSelingById($productStreamIDs);
            $elements = $this->loadByStream($stream)->getElements();
            foreach ($elements as $element) {
                if ($element->getAvailable()) {
                    $sliderProducts[] = $element;
                }
            }
        }

        return $sliderProducts;
    }

    /**
     * @param string $productNumber
     * @param SalesChannelContext $salesChannelContext
     * @param string $groupName
     * @return array
     */
    private function loadCrossSellings(string $productNumber, SalesChannelContext $salesChannelContext, string $groupName): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('product.productNumber', $productNumber));

        $productData = $this->salesChannelProductRepository
            ->search($criteria, $salesChannelContext)
            ->getEntities();

        $productId = $productData->first()->getId();

        if (!empty($productData->first()->getParentId())) {
            $productId = $productData->first()->getParentId();
        }

//  get all crosssellings for this product

        $criteria = new Criteria();
        $criteria
            ->addAssociation('assignedProducts.product')
            ->addFilter(new EqualsFilter('product.id', $productId))
            ->addFilter(new EqualsFilter('active', 1))
            ->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));

        $crossSellings = $this->crossSellingRepository
            ->search($criteria, $salesChannelContext->getContext())
            ->getEntities();

        $crossellingProducts = [];
        if (!empty($crossSellings->getElements())) {
            foreach ($crossSellings->getElements() as $crossSelling) {
                $equals = $this->compareCrossSellingsNames($crossSelling->getId(), $groupName);

//  checks the type (productStream, productList) of cross-selling and generate data
                if ($crossSelling->getType() == 'productList') {
                    if ($equals) {
                        foreach ($crossSelling->getAssignedProducts()->getElements() as $element) {
                            if (!empty($element->getProductId())) {
                                $product = $this->getProductById($element->getProductId());
                                if (!empty($product) && $product->getAvailable()) {
                                    $crossellingProducts[] = $product;
                                }
                            }
                        }
                    }
                } elseif ($crossSelling->getType() == 'productStream') {
                    $product = $this->loadByStream($crossSelling);
                    if ($equals && !empty($product)) {
                        foreach (array_values($product->getElements()) as $similarProduct) {
                            if (!empty($similarProduct) && $similarProduct->getAvailable()) {
                                $crossellingProducts[] = $similarProduct;
                            }
                        }
                    }
                }
            }
        }

        return $crossellingProducts;
    }

    /**
     * @param string $id
     * @return SalesChannelProductEntity|null
     */
    private function getProductById(string $id)
    {
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('media');
        $entities = $this->salesChannelProductRepository->search($criteria, $this->salesChannelContext);

        return $entities->first();
    }

    /**
     * @param array $ids
     * @return ProductStreamEntity|null
     */
    private function getCrossSelingById(array $ids)
    {
        $criteria = new Criteria([$ids[0]]);
        $productStream = $this->productStreamRepository->search($criteria, $this->salesChannelContext->getContext());

        return $productStream->first();
    }

    /**
     * @param $crossSelling
     * @return \Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult
     */
    private function loadByStream($crossSelling)
    {
//  get crosselling by stream data
        if (method_exists($crossSelling, 'getProductStreamId')) {
            $productStreamId = $crossSelling->getProductStreamId();
        } else {
            $productStreamId = $crossSelling->getId();
        }

        if (!$productStreamId) {
            return null;
        }

        $filters = $this->productStreamBuilder->buildFilters(
            $productStreamId,
            $this->salesChannelContext->getContext()
        );

        $limit = 50;
        if (method_exists($crossSelling, 'getLimit')) {
            $limit = $crossSelling->getLimit();
        }

        $criteria = new Criteria();
        $criteria->addFilter(...$filters)
            ->setLimit($limit);

        $criteria = $this->handleAvailableStock($criteria);
        return $this->salesChannelProductRepository->search($criteria, $this->salesChannelContext);

    }

    /**
     * @param Criteria $criteria
     * @return Criteria
     */
    private function handleAvailableStock(Criteria $criteria): Criteria
    {
        $salesChannelId = $this->salesChannelContext->getSalesChannel()->getId();
        $hide = $this->configService->get('core.listing.hideCloseoutProductsWhenOutOfStock', $salesChannelId);

        if (!$hide) {
            return $criteria;
        }

        $criteria->addFilter(new ProductCloseoutFilter());

        return $criteria;
    }

    /**
     * @param string $number
     * @return array|false|mixed
     */
    private function getArticleAssocFromNumber(string $number)
    {
        $articleAssoc = [];
        $criteria = new Criteria([$number]);
        $product = $this->salesChannelProductRepository->search($criteria, $this->salesChannelContext);

        if (!empty($product->first()) && $product->first()->getAvailable()) {
            $articleAssoc = $product->first();
        }

        return $articleAssoc;
    }

    /**
     * @param string $id
     * @param string $groupName
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    private function compareCrossSellingsNames(string $id, string $groupName): bool
    {
        $namesArray = $this->connection
            ->executeQuery('SELECT name FROM product_cross_selling_translation WHERE product_cross_selling_id = :id', [
                'id' => Uuid::fromHexToBytes($id)
            ])
            ->fetchAllAssociative();

        foreach ($namesArray as $names) {
            if ($names['name'] == $groupName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param SalesChannelContext $salesChannelContext
     * @param string $groupName
     * @return array
     */
    private function getProducts(SalesChannelContext $salesChannelContext, string $groupName): array
    {
        $products = [];
        foreach ($this->cartItemNumbers as $cartItemNumber) {
            if (empty($products[$cartItemNumber])) {
                $crossSellingData = $this->loadCrossSellings($cartItemNumber, $salesChannelContext, $groupName);
                if (!empty($crossSellingData)) {
                    foreach ($crossSellingData as $crossSellingProduct) {
                        $products[$crossSellingProduct->getProductNumber()] = $crossSellingProduct;
                    }
                }
            }
        }

        return $products;
    }
}

