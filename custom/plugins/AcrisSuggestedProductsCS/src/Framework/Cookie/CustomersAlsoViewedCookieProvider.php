<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Framework\Cookie;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CustomersAlsoViewedCookieProvider implements CookieProviderInterface
{
    public const COOKIE_ID = 'acris_customers_also_viewed';

    private $originalService;

    public function __construct(CookieProviderInterface $service)
    {
        $this->originalService = $service;
    }

    private const customersAlsoViewedCookie = [
        'snippet_name' => 'acrisSuggestedProducts.cookie.nameCustomersAlsoViewed',
        'snippet_description' => 'acrisSuggestedProducts.cookie.descriptionCustomersAlsoViewed',
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
                $group['entries'] = \array_merge($group['entries'], [self::customersAlsoViewedCookie]);
            }
            $groupsNew[] = $group;
        }

        return $groupsNew;
    }
}
