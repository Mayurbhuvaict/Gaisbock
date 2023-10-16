<?php

declare(strict_types=1);

namespace Tanmar\NgInfiniteScrolling\Service;

use Shopware\Core\PlatformRequest;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\RequestStack;
use Tanmar\NgInfiniteScrolling\Components\Config;

class ConfigService {

    private SystemConfigService $systemConfigService;
    private Config $config;
    private array $configs;

    public function __construct(SystemConfigService $systemConfigService, RequestStack $requestStack) {
        $this->systemConfigService = $systemConfigService;

        $salesChannelId = $this->getSalesChannelId($requestStack);

        $this->configs = [];
        $this->config = $this->getSalesChannelConfig($salesChannelId);
    }

    private function getSalesChannelId(RequestStack $requestStack): ?string {
        if (!is_null($requestStack->getCurrentRequest()) && !is_null($requestStack->getCurrentRequest()->attributes)) {
            $salesChannelId = $requestStack->getCurrentRequest()->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID);
            if (!is_null($salesChannelId)) {
                return $salesChannelId;
            }
        }
        return null;
    }

    public function getConfig(): Config {
        return $this->config;
    }

    public function getSalesChannelConfig(?string $salesChannelId): Config {
        if ($salesChannelId === null) {
            return new Config($this->systemConfigService, $salesChannelId);
        }
        if (!isset($this->configs[$salesChannelId])) {
            $this->configs[$salesChannelId] = new Config($this->systemConfigService, $salesChannelId);
        }
        return $this->configs[$salesChannelId];
    }

}
