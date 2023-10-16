<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Core\Content\Product\Events;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\Framework\Event\ShopwareSalesChannelEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class RecentlyViewedProductsResultEvent extends NestedEvent implements ShopwareSalesChannelEvent
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SalesChannelContext
     */
    protected $context;

    /**
     * @var array
     */
    protected $result;

    public function __construct(Request $request, object $result, SalesChannelContext $context)
    {
        $this->request = $request;
        $this->context = $context;
        $this->result = $result;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getContext(): Context
    {
        return $this->context->getContext();
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
