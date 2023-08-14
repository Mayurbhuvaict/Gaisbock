<?php

namespace Gaisbock\Storefront\Page;

use JetBrains\PhpStorm\NoReturn;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
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
        private EntityRepository $productRepository
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
        $data=[];
        $categoryData=[];
        $path = $event->getPage()->getHeader()->getNavigation()->getActive()->getPath();
        $activeId = $event->getPage()->getHeader()->getNavigation()->getActive()->getId();
        $getActiveCategory = '';
        // get level 2 all categories...
        $categoryCriteria = new Criteria();
        $categoryCriteria->addFilter(new EqualsFilter('level',2));
        $category = $this->categoryRepository->search($categoryCriteria,$event->getContext())->getElements();
        // check active level 2 category...
        foreach ($category as $item){
            if (str_contains($path, $item->getId()) || $activeId == $item->getId()){
                $getActiveCategory = $item->getId();
            }
        }
        // get child category of level 2 active category...
        if ($getActiveCategory) {
            $categoryDataCriteria = new Criteria();
            $categoryDataCriteria->addFilter(new EqualsFilter('parentId', $getActiveCategory));
            $categoryCriteria->addFilter(new EqualsFilter('level',3));
            $categoryDataCriteria->addAssociation('media');
            $categoryData = $this->categoryRepository->search($categoryDataCriteria, $event->getContext())->getElements();
        }
        $data = [
            'data' => $categoryData,
        ];
        $event->getPage()->addExtension('categoryData',new ArrayStruct($data));
    }

    public function productPageLoaded(ProductPageLoadedEvent $event):void
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
        if ($getProductCustomImages['customFields'])
        {

            if (array_key_exists('gaisbock_product_detail_description_set_image1',$getProductCustomImages['customFields']))
            {
                $image1 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image1'];
                $images['1'] = $this->findMedia($image1,$event->getContext());

            }
            if (array_key_exists('gaisbock_product_detail_description_set_image2',$getProductCustomImages['customFields']))
            {
                $image2 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image2'];
                $images['2'] = $this->findMedia($image2,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image3',$getProductCustomImages['customFields']))
            {
                $image3 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image3'];
                $images['3'] = $this->findMedia($image3,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image4',$getProductCustomImages['customFields']))
            {
                $image4 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image4'];
                $images['4'] = $this->findMedia($image4,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image5',$getProductCustomImages['customFields']))
            {
                $image5 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image5'];
                $images['5'] = $this->findMedia($image5,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image6',$getProductCustomImages['customFields']))
            {
                $image6 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image6'];
                $images['6'] = $this->findMedia($image6,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image7',$getProductCustomImages['customFields']))
            {
                $image7 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image7'];
                $images['7'] = $this->findMedia($image7,$event->getContext());
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image8',$getProductCustomImages['customFields']))
            {
                $image8 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image8'];
                $images['8'] = $this->findMedia($image8,$event->getContext());
            }
            $event->getPage()->getProduct()->addArrayExtension('images',['customImage'=>$images]);
        }

        $variantProducts = $this->variantProductImages($event,$event->getContext());
        $event->getPage()->addArrayExtension('products',['variants'=>$variantProducts]);
    }

    private function variantProductImages($event,$context)
    {
        $getVariant = $event->getPage()->getProduct()->getParentId();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('parentId',$getVariant));
        $products = $this->productRepository->search($criteria,$context)->getElements();
        return $products;
    }

    private function findMedia($mediaId,$context)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $mediaId));
        $getImage = $this->mediaRepository->search($criteria, $context)->first();
        return $getImage;
    }
}