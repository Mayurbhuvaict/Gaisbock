<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Decorator;

use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CookieProvider implements CookieProviderInterface
{
    public function __construct(
        private readonly CookieProviderInterface $mainService,
        private readonly PluginConfigStruct      $pluginConfig
    ) {
    }

    public function getCookieGroups(): array
    {
        return array_merge(
            $this->mainService->getCookieGroups(),
            [
                [
                    'snippet_name'        => 'neti-next-store-locator.cookie.google-consent.name',
                    'snippet_description' => 'neti-next-store-locator.cookie.google-consent.description',
                    'cookie'              => 'neti-store-locator-google-consent',
                    'value'               => 'available',
                    'expiration'          => $this->pluginConfig->getCookieLifetime(),
                ],
            ]
        );
    }
}
