<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Service;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CustomCookieProvider implements CookieProviderInterface {

    function __construct(private readonly CookieProviderInterface $originalService)
    {
    }

    private const popupCookie = [
        'isRequired' => true,
        'snippet_name' => 'neno-marketing-essentials.cookies.popupCookie.name',
        'snippet_description' => 'neno-marketing-essentials.cookies.popupCookie.description',
        'cookie' => '*-closed',
    ];

    public function getCookieGroups(): array
    {
        return array_merge(
            $this->originalService->getCookieGroups(),
            [
                self::popupCookie,
            ]
        );
    }
}
