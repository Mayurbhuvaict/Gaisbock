<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class StoreListingPageLoadedEvent extends PageLoadedEvent
{
    public function __construct(
        protected readonly StoreListingPage $page,
        SalesChannelContext                 $salesChannelContext,
        Request                             $request
    ) {
        parent::__construct($salesChannelContext, $request);
    }

    public function getPage(): StoreListingPage
    {
        return $this->page;
    }
}
