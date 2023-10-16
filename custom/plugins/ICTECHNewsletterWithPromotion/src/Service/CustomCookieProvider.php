<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Service;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CustomCookieProvider implements CookieProviderInterface
{
    private const POPUPCOOKIE = [
        'isRequired' => true,
        'snippet_name' => 'ict-newsletter-with-promotion.cookies.popupCookie.name',
        'snippet_description' => 'ict-newsletter-with-promotion.cookies.popupCookie.description',
        'cookie' => '*-closed',
    ];

    public function __construct(
        private readonly CookieProviderInterface $originalService
    ) {
    }

    public function getCookieGroups(): array
    {
        return array_merge(
            $this->originalService->getCookieGroups(),
            [
                self::POPUPCOOKIE,
            ]
        );
    }
}
