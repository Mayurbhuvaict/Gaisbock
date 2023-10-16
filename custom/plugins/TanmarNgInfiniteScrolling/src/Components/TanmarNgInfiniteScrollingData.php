<?php

declare(strict_types=1);

namespace Tanmar\NgInfiniteScrolling\Components;

use Shopware\Core\Framework\Struct\Struct;

class TanmarNgInfiniteScrollingData extends Struct {

    protected $active;
    protected $data;
    protected $pages;
    protected $customProduct;
    protected $customPrepend;
    protected $customAppend;
    protected $rootMargin;
    protected $threshold;
    protected $debug;
    protected $triggerAfterRenderResponseEvent;
    protected $onlyObserveWithinListingWrapper;
    protected $customPaginationSelector;

    public function __construct() {
        $this->active = false;
        $this->data = [];
        
        $this->pages = 2;
        $this->customProduct = '';
        $this->customPrepend = '';
        $this->customAppend = '';
        $this->rootMargin = '0px';
        $this->threshold = '0.5';
        $this->debug = false;
        $this->triggerAfterRenderResponseEvent = false;
        $this->onlyObserveWithinListingWrapper = false;
        $this->customPaginationSelector = '';
    }

    public function getActive(): bool {
        return $this->active;
    }

    public function getData(): array {
        return $this->data;
    }

    public function getPages(): int {
        return $this->pages;
    }

    public function getCustomProduct(): string{
        return $this->customProduct;
    }

    public function getCustomPrepend(): string {
        return $this->customPrepend;
    }

    public function getCustomAppend(): string {
        return $this->customAppend;
    }

    public function getRootMargin(): string {
        return $this->rootMargin;
    }

    public function getThreshold(): string {
        return $this->threshold;
    }

    public function isDebug(): bool {
        return $this->debug;
    }

    public function isTriggerAfterRenderResponseEvent(): bool {
        return $this->triggerAfterRenderResponseEvent;
    }

    public function getOnlyObserveWithinListingWrapper(): bool{
        return $this->onlyObserveWithinListingWrapper;
    }
    
    public function getCustomPaginationSelector(): string {
        return $this->customPaginationSelector;
    }
}
