<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Decorator;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CookieProvider implements CookieProviderInterface
{
    private const cookie = [
        'snippet_name' => 'neti-next-language-detector.cookie.name',
        'cookie'       => 'language-detector-redirect',
        'expiration'   => '365',
        'isRequired'   => true,
    ];

    public function __construct(
        private readonly CookieProviderInterface $originalService
    ) {
    }

    public function getCookieGroups(): array
    {
        $cookies = $this->originalService->getCookieGroups();

        foreach ($cookies as &$cookie) {
            if (!is_array($cookie)) {
                continue;
            }

            if (!$this->isRequiredCookieGroup($cookie)) {
                continue;
            }

            if (!is_array($cookie['entries']) || !array_key_exists('entries', $cookie)) {
                continue;
            }

            $cookie['entries'][] = self::cookie;
        }

        return $cookies;
    }

    private function isRequiredCookieGroup(array $cookie): bool
    {
        return (\array_key_exists('isRequired', $cookie) && $cookie['isRequired'] === true)
            && (\array_key_exists('snippet_name', $cookie) && $cookie['snippet_name'] === 'cookie.groupRequired');
    }
}
