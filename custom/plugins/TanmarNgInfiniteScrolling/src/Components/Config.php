<?php

declare(strict_types=1);

namespace Tanmar\NgInfiniteScrolling\Components;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class Config {

    public const PLUGIN_NAME = 'TanmarNgInfiniteScrolling';
    
    protected SystemConfigService $systemConfigService;
    protected $salesChannelId;
    
    protected $path;
    protected $active;
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

    public function __construct(SystemConfigService $systemConfigService, ?string $salesChannelId) {
        $this->path = self::PLUGIN_NAME . '.config.';
        $this->systemConfigService = $systemConfigService;
        $this->salesChannelId = $salesChannelId;
        
        $this->active = $this->get('active', false);
        $this->pages = $this->get('pages', 2);
        $this->customProduct = $this->get('customProductSelector', '');
        $this->customPrepend = $this->get('customPrepend', '');
        $this->customAppend = $this->get('customAppend', '');
        
        $this->rootMargin = (string)$this->get('rootMargin', '');
        if(trim($this->rootMargin) == ''){
            $this->rootMargin = '0px';
        }
        $this->threshold = (string)$this->get('threshold', '');
        if(trim($this->threshold) == ''){
            $this->threshold = '0.5';
        }
        
        $this->debug = $this->get('debug', false);
        $this->triggerAfterRenderResponseEvent = $this->get('triggerAfterRenderResponseEvent', false);
        $this->onlyObserveWithinListingWrapper = $this->get('onlyObserveWithinListingWrapper', false);
        
        $this->customPaginationSelector = (string)$this->get('customPaginationSelector', '.pagination-nav');
        if(trim($this->customPaginationSelector) == ''){
            $this->customPaginationSelector = '.pagination-nav';
        }
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function getPages(): int {
        return $this->pages;
    }

    public function getCustomProduct(): string {
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
    
    private function get(string $configValueName, $defaultValue = false) {
        $configValueSalesChannel = $this->systemConfigService->get($this->path . $configValueName, $this->salesChannelId);
        if (!is_null($configValueSalesChannel)) {
            return $configValueSalesChannel;
        } else {
            return $defaultValue;
        }
    }
}
