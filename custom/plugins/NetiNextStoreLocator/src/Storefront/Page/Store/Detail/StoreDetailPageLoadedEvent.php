<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Detail;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class StoreDetailPageLoadedEvent extends PageLoadedEvent
{
    public function __construct(
        protected readonly StoreDetailPage $page,
        SalesChannelContext                $salesChannelContext,
        Request                            $request
    ) {
        parent::__construct($salesChannelContext, $request);
    }

    public function getPage(): StoreDetailPage
    {
        return $this->page;
    }
}
