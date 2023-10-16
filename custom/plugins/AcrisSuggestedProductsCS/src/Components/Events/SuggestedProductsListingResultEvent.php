<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Components\Events;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\Framework\Event\ShopwareSalesChannelEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class SuggestedProductsListingResultEvent extends NestedEvent implements ShopwareSalesChannelEvent
{
    public const EVENT_NAME = 'acris_suggested_products.listing.result';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SalesChannelContext
     */
    protected $context;

    /**
     * @var EntitySearchResult
     */
    protected $result;

    public function __construct(Request $request, EntitySearchResult $result, SalesChannelContext $context)
    {
        $this->request = $request;
        $this->context = $context;
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
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

    public function getResult(): EntitySearchResult
    {
        return $this->result;
    }
}
