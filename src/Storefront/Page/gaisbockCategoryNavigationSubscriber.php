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
        private EntityRepository $mediaRepository
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

        // Get child categories
        foreach ($categoryData as $value) {

            $subCategoryCriteria = new Criteria();
            $subCategoryCriteria->addFilter(new EqualsFilter('parentId',$value->getId()));
            $subCategoryCriteria->addAssociation('media');
            $subCategory[$value->getId()] = $this->categoryRepository->search($subCategoryCriteria, $event->getContext())->getElements();
        }
        $data = [
            'data' => $categoryData,
            'subCategoryData'=> $subCategory
        ];
        $event->getPage()->addExtension('categoryData',new ArrayStruct($data));
    }

    public function productPageLoaded(ProductPageLoadedEvent $event):void
    {
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

            }
            if (array_key_exists('gaisbock_product_detail_description_set_image2',$getProductCustomImages['customFields']))
            {
                $image2 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image2'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image3',$getProductCustomImages['customFields']))
            {
                $image3 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image3'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image4',$getProductCustomImages['customFields']))
            {
                $image4 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image4'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image5',$getProductCustomImages['customFields']))
            {
                $image5 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image5'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image6',$getProductCustomImages['customFields']))
            {
                $image6 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image6'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image7',$getProductCustomImages['customFields']))
            {
                $image7 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image7'];
            }
            if (array_key_exists('gaisbock_product_detail_description_set_image8',$getProductCustomImages['customFields']))
            {
                $image8 = $getProductCustomImages['customFields']['gaisbock_product_detail_description_set_image8'];
            }

        }

    }
}