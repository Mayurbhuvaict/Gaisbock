<?php

namespace Gaisbock\Storefront\Page;

use JetBrains\PhpStorm\NoReturn;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[Package('core')]
class gaisbockCategoryNavigationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $categoryRepository,
        private EntityRepository $mediaRepository,
        private readonly SalesChannelRepository $salesChannelProductRepository
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            NavigationPageLoadedEvent::class => 'NavigationPageLoaded',
            ProductPageLoadedEvent::class => 'productPageLoaded'
        ];
    }

    public function NavigationPageLoaded(NavigationPageLoadedEvent $event): void
    {
        $subCategory = [];
        $data = [];
        $categoryData = [];
        $path = $event->getPage()->getHeader()->getNavigation()->getActive()->getPath();
        $activeId = $event->getPage()->getHeader()->getNavigation()->getActive()->getId();
        $getActiveCategory = '';
        // get level 2 all categories...
        $categoryCriteria = new Criteria();
        $categoryCriteria->addFilter(new EqualsFilter('level', 2));
        $category = $this->categoryRepository->search($categoryCriteria, $event->getContext())->getElements();
        // check active level 2 category...
        foreach ($category as $item) {
            if (str_contains($path, $item->getId()) || $activeId == $item->getId()) {
                $getActiveCategory = $item->getId();
            }
        }
        // get child category of level 2 active category...
        if ($getActiveCategory) {
            $categoryDataCriteria = new Criteria();
            $categoryDataCriteria->addFilter(new MultiFilter(
                MultiFilter::CONNECTION_AND,
                [
                    new EqualsFilter('parentId', $getActiveCategory),
                    new EqualsFilter('level', 3),
                    new EqualsFilter('active',true)
                ])
            );
            $categoryDataCriteria->addAssociation('media');
            $categoryData = $this->categoryRepository->search($categoryDataCriteria, $event->getContext())->getElements();
        }
        $data = [
            'activeCategory' => $getActiveCategory,
            'data' => $categoryData,
        ];
        $event->getPage()->addExtension('categoryData', new ArrayStruct($data));
    }

    public function productPageLoaded(ProductPageLoadedEvent $event): void
    {
        $images = [];
        $image1 = '';
        $image2 = '';
        $image3 = '';
        $image4 = '';
        $image5 = '';
        $image6 = '';
        $image7 = '';
        $image8 = '';

        $getProductCustomImages = $event->getPage()->getProduct()->getTranslated();
        if ($getProductCustomImages['customFields']) {

            if (array_key_exists('gaisbock_product_detail_description_set_image1', $getProductCustomImages['customFields'])) {
                $image1 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image1'];
                $images['1'] = $this->findMedia($image1, $event->getContext());

            }
            if (array_key_exists('gaisbock_product_detail_description_set_image2', $getProductCustomImages['customFields'])) {
                $image2 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image2'];
                $images['2'] = $this->findMedia($image2, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image3', $getProductCustomImages['customFields'])) {
                $image3 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image3'];
                $images['3'] = $this->findMedia($image3, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image4', $getProductCustomImages['customFields'])) {
                $image4 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image4'];
                $images['4'] = $this->findMedia($image4, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image5', $getProductCustomImages['customFields'])) {
                $image5 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image5'];
                $images['5'] = $this->findMedia($image5, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image6', $getProductCustomImages['customFields'])) {
                $image6 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image6'];
                $images['6'] = $this->findMedia($image6, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image7', $getProductCustomImages['customFields'])) {
                $image7 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image7'];
                $images['7'] = $this->findMedia($image7, $event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image8', $getProductCustomImages['customFields'])) {
                $image8 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image8'];
                $images['8'] = $this->findMedia($image8, $event->getContext());
            }
            $event->getPage()->getProduct()->addArrayExtension('images', ['customImage' => $images]);
        }
        $productName = '';
        $getVariant = $event->getPage()->getProduct()->getParentId();
        if ($getVariant != null)
        {
            $productName = $this->findParentProduct($getVariant,$event->getSalesChannelContext());
        }
        $variantProducts = $this->variantProductImages($event, $event->getSalesChannelContext());
        $productNumber = $this->findProductNumber($event, $event->getSalesChannelContext());
        $grosseProducts = $this->findGrosseProduct($event, $event->getSalesChannelContext());
        $event->getPage()->addArrayExtension('products', [
            'variants' => $variantProducts['products'],
            'VariantImage' => $variantProducts['images'],
            'productName' => $productName,
            'productNumber' => $productNumber,
//            'grosseProduct' => $grosseProducts
        ]);
    }

    private function variantProductImages($event, $context)
    {
        $variantProductImage = [];
        $productImage = [];
        $getVariant = $event->getPage()->getProduct()->getParentId();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('parentId', $getVariant));
        $criteria->addAssociation('options');
        $criteria->addAssociation('calculatedPrice');
        $products = $this->salesChannelProductRepository->search($criteria, $context)->getElements();
        foreach ($products as $product) {
            $images = [];
            $image1 = '';
            $image2 = '';
            $image3 = '';
            $image4 = '';
            $image5 = '';
            $image6 = '';
            $image7 = '';
            $image8 = '';

            $getProductCustomImages = $product->getTranslated();

            if ($getProductCustomImages['customFields']) {

                if (array_key_exists('gaisbock_product_variant_image1', $getProductCustomImages['customFields'])) {
                    $image1 = $getProductCustomImages['customFields']['gaisbock_product_variant_image1'];
                    $images['1'] = ['image' => $this->findMedia($image1, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text1']];

                }
                if (array_key_exists('gaisbock_product_variant_image2', $getProductCustomImages['customFields'])) {
                    $image2 = $getProductCustomImages['customFields']['gaisbock_product_variant_image2'];
                    $images['2'] = ['image' => $this->findMedia($image2, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text2']];
                }
                if (array_key_exists('gaisbock_product_variant_image3', $getProductCustomImages['customFields'])) {
                    $image3 = $getProductCustomImages['customFields']['gaisbock_product_variant_image3'];
                    $images['3'] = ['image' => $this->findMedia($image3, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text3']];
                }
                if (array_key_exists('gaisbock_product_variant_image4', $getProductCustomImages['customFields'])) {
                    $image4 = $getProductCustomImages['customFields']['gaisbock_product_variant_image4'];
                    $images['4'] = ['image' => $this->findMedia($image4, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text4']];
                }
                if (array_key_exists('gaisbock_product_variant_image5', $getProductCustomImages['customFields'])) {
                    $image5 = $getProductCustomImages['customFields']['gaisbock_product_variant_image5'];
                    $images['5'] = ['image' => $this->findMedia($image5, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text5']];
                }
                if (array_key_exists('gaisbock_product_variant_image6', $getProductCustomImages['customFields'])) {
                    $image6 = $getProductCustomImages['customFields']['gaisbock_product_variant_image6'];
                    $images['6'] = ['image' => $this->findMedia($image6, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text6']];
                }
                if (array_key_exists('gaisbock_product_variant_image7', $getProductCustomImages['customFields'])) {
                    $image7 = $getProductCustomImages['customFields']['gaisbock_product_variant_image7'];
                    $images['7'] = ['image' => $this->findMedia($image7, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text7']];
                }
                if (array_key_exists('gaisbock_product_variant_image8', $getProductCustomImages['customFields'])) {
                    $image8 = $getProductCustomImages['customFields']['gaisbock_product_variant_image8'];
                    $images['8'] = ['image' => $this->findMedia($image8, $event->getContext()),'text'=>$getProductCustomImages['customFields']['gaisbock_product_variant_text8']];
                }

                $productImage[$product->getId()] =  $images;
            }
        }

        $variantProductImage = [
            'products' => $products,
            'images' => $productImage
        ];
        return $variantProductImage;
    }

    private function findProductNumber($event, $context){
        $criteria = new Criteria();
        $getProductId = $event->getPage()->getProduct()->getParentId();
        $criteria->addFilter(new EqualsFilter('id',$getProductId));
        $criteria->addAssociation('configuratorSettings');
        $criteria->addAssociation('configuratorSettings.option');
        if ($this->salesChannelProductRepository->search($criteria,$context)->first()) {
            return $this->salesChannelProductRepository->search($criteria, $context)->first()->getProductNumber();
        }else{
            return null;
        }
    }

    private function findMedia($mediaId, $context)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $mediaId));
        $getImage = $this->mediaRepository->search($criteria, $context)->first();
        return $getImage;
    }

    private function findParentProduct($paraentId,$context)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id',$paraentId));
        $product = $this->salesChannelProductRepository->search($criteria,$context)->first();
        return $product->getTranslated()['name'];
    }
    private function findGrosseProduct($event, $context)
    {
        if (array_key_exists('gaisbockProducts',$event->getPage()->getProduct()->getTranslated()['customFields'])) {
            $grosseProduct = $event->getPage()->getProduct()->getTranslated()['customFields']['gaisbockProducts'];
            if($grosseProduct != null){
                $productCriteria = new Criteria();
                $productCriteria->addFilter(new EqualsFilter('parentId', $grosseProduct));
                $productCriteria->addAssociation('options');
                $product = $this->salesChannelProductRepository->search($productCriteria, $context)->getElements();
                return $product;
            }

        }
    }
}