<?php declare(strict_types=1);

namespace Swag\Security\Tests\Helper;

/**
 * @internal
 */
class UrlHelper
{
    public static function getApiUrl(string $url, bool $appendAppUrl = true): string
    {
        return ($appendAppUrl ? $_SERVER['APP_URL'] : '') . $url;
    }
}
