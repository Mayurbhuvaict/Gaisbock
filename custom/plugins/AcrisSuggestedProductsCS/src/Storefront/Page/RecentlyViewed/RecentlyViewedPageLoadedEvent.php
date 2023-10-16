<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Storefront\Page\RecentlyViewed;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class RecentlyViewedPageLoadedEvent extends PageLoadedEvent
{
    /**
     * @var RecentlyViewedPage
     */
    protected $page;

    public function __construct(RecentlyViewedPage $page, SalesChannelContext $salesChannelContext, Request $request)
    {
        $this->page = $page;
        parent::__construct($salesChannelContext, $request);
    }

    public function getPage(): RecentlyViewedPage
    {
        return $this->page;
    }
}
