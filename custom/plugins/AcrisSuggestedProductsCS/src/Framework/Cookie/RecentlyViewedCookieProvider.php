<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Framework\Cookie;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class RecentlyViewedCookieProvider implements CookieProviderInterface
{
    public const COOKIE_ID = 'acris_recently_viewed';

    private $originalService;

    public function __construct(CookieProviderInterface $service)
    {
        $this->originalService = $service;
    }

    private const recentlyViewedCookie = [
        'snippet_name' => 'acrisSuggestedProducts.cookie.nameRecentlyViewed',
        'snippet_description' => 'acrisSuggestedProducts.cookie.descriptionRecentlyViewed',
        'cookie' => self::COOKIE_ID,
        'value' => true,
        'expiration' => '30'
    ];

    public function getCookieGroups(): array
    {

       $groups = $this->originalService->getCookieGroups();
       $groupsNew = [];

       foreach ($groups as $group) {
           if ($group['snippet_name'] == 'cookie.groupComfortFeatures') {
               $group['entries'] = \array_merge($group['entries'], [self::recentlyViewedCookie]);
           }
           $groupsNew[] = $group;
       }

       return $groupsNew;
    }
}
