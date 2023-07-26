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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[Package('core')]
class gaisbockCategoryNavigationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $categoryRepository
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            NavigationPageLoadedEvent::class => 'NavigationPageLoaded'
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
}