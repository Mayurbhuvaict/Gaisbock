<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class StoreDetailLoader
{
    public function __construct(
        private readonly EntityRepository $repository
    ) {
    }

    public function load(string $storeId, SalesChannelContext $context): StoreEntity
    {
        $criteria = (new Criteria([ $storeId ]))
            ->addAssociation('country')
            ->addAssociation('countryState')
            ->addAssociation('pictureMedia')
            ->addAssociation('iconMedia')
            ->addAssociation('tags')
            ->addAssociation('cmsPages.cmsPage')
            ->addAssociation('detailsPictureMedia');

        /** @var StoreEntity|null $store */
        $store = $this->repository->search($criteria, $context->getContext())->get($storeId);

        if (null === $store) {
            throw new \Exception('Store not found.');
        }

        return $store;
    }
}
