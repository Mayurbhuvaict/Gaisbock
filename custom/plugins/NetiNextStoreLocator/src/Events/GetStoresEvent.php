<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Events;

use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\EventDispatcher\Event;

class GetStoresEvent extends Event
{
    public function __construct(
        private EntitySearchResult  $storesSearchResult,
        private SalesChannelContext $salesChannelContext
    ) {
    }

    /**
     * @psalm-mutation-free
     */
    public function getStoresSearchResult(): EntitySearchResult
    {
        return $this->storesSearchResult;
    }

    public function setStoresSearchResult(EntitySearchResult $storesSearchResult): void
    {
        $this->storesSearchResult = $storesSearchResult;
    }

    /**
     * @psalm-mutation-free
     */
    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }

    public function setSalesChannelContext(SalesChannelContext $salesChannelContext): void
    {
        $this->salesChannelContext = $salesChannelContext;
    }
}
