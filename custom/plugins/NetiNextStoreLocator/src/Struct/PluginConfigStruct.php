<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Struct;

class PluginConfigStruct
{
    final public const COUNTRY_SORT_BY_NAME_ASC                          = 'name_asc';

    final public const COUNTRY_SORT_BY_NAME_DESC                         = 'name_desc';

    final public const COUNTRY_SORT_BY_POSITION_ASC                      = 'position_asc';

    final public const COUNTRY_SORT_BY_POSITION_DESC                     = 'position_desc';

    final public const ORDER_FINISH_ADDRESS_TYPE_SHIPPING                = 'shipping';

    final public const ORDER_FINISH_ADDRESS_TYPE_BILLING                 = 'billing';

    final public const ORDER_FINISH_DISPLAY_TYPE_LIST                    = 'list';

    final public const AUTOCOMPLETE_RESULT_PRIORITY_PLACES_BEFORE_STORES = 'places_before_stores';

    final public const AUTOCOMPLETE_RESULT_PRIORITY_STORES_BEFORE_PLACES = 'stores_before_places';

    private bool    $active                               = true;

    private string  $seoUrl                               = '';

    private bool    $detailPage                           = false;

    private string  $googleApiKey                         = '';

    private string  $googleWorkApiKey                     = '';

    private ?string $googleApiLang                        = null;

    private bool    $googleApiLangInheritFromDomain       = false;

    private bool    $googleApiTimezoneEnabled             = false;

    private string  $getGeolocation                       = 'always';

    private bool    $googleMarkerClustering               = false;

    private string  $contactSubjectOptions                = '';

    private string  $googleMapOptions                     = '';

    private bool    $restrictFeaturedStoresToCountry      = false;

    private string  $distanceUnit                         = 'km';

    private bool    $showLocalMarker                      = false;

    private bool    $focusLocalMarker                     = false;

    private bool    $forceAjaxSearch                      = false;

    private string  $searchRadiusValues                   = '25;50;100;250;500';

    private int     $defaultSearchRadius                  = 25;

    private bool    $filterHighlightedStoresByRadiusFirst = false;

    private bool    $autoloadStores                       = true;

    private int     $searchResultLimit                    = 100;

    private string  $googleMapIconSize                    = '';

    private bool    $routeOnGoogleMaps                    = false;

    private bool    $showCountryFilter                    = false;

    private string  $countrySortBy                        = self::COUNTRY_SORT_BY_NAME_ASC;

    private ?string $googleMapIcon                        = null;

    private ?string $preselectedCountryId                 = null;

    private ?string $topCmsPage                           = null;

    private ?string $bottomCmsPage                        = null;

    private bool    $showOpeningTimesOnListing            = false;

    private bool    $showOpeningTimesOnMarkerWindow       = false;

    private bool    $requireCookieForGoogleMaps           = false;

    private ?string $breadcrumbCategory                   = null;

    private bool    $showIndividualFiltersInOffCanvas     = false;

    private string  $filterTagMode                        = 'one';

    private bool    $clearSearchOnCountryChange           = false;

    private bool    $filterShowAlways                     = false;

    private bool    $showStoresOnOrderFinish              = false;

    private int     $orderFinishStoreCount                = 5;

    private string  $orderFinishAddressType               = self::ORDER_FINISH_ADDRESS_TYPE_SHIPPING;

    private string  $orderFinishDisplayType               = self::ORDER_FINISH_DISPLAY_TYPE_LIST;

    private int     $cookieLifetime                       = 30;

    private string  $googleMapConfig                      = '';

    private string  $googleMapID                          = '';

    private string  $autocompleteResultPriority           = self::AUTOCOMPLETE_RESULT_PRIORITY_PLACES_BEFORE_STORES;

    /**
     * @psalm-mutation-free
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @psalm-mutation-free
     */
    public function getSeoUrl(): string
    {
        return $this->seoUrl;
    }

    /**
     * @psalm-mutation-free
     */
    public function isDetailPage(): bool
    {
        return $this->detailPage;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleApiKey(): string
    {
        return $this->googleApiKey;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleWorkApiKey(): string
    {
        return $this->googleWorkApiKey;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleApiLang(): ?string
    {
        return $this->googleApiLang;
    }

    /**
     * @psalm-mutation-free
     */
    public function isGoogleApiLangInheritFromDomain(): bool
    {
        return $this->googleApiLangInheritFromDomain;
    }

    /**
     * @psalm-mutation-free
     */
    public function isGoogleApiTimezoneEnabled(): bool
    {
        return $this->googleApiTimezoneEnabled;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGetGeolocation(): string
    {
        return $this->getGeolocation;
    }

    /**
     * @psalm-mutation-free
     */
    public function isGoogleMarkerClustering(): bool
    {
        return $this->googleMarkerClustering;
    }

    /**
     * @psalm-mutation-free
     */
    public function getContactSubjectOptions(): string
    {
        return $this->contactSubjectOptions;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleMapOptions(): string
    {
        return $this->googleMapOptions;
    }

    /**
     * @psalm-mutation-free
     */
    public function isRestrictFeaturedStoresToCountry(): bool
    {
        return $this->restrictFeaturedStoresToCountry;
    }

    /**
     * @psalm-mutation-free
     */
    public function getDistanceUnit(): string
    {
        return $this->distanceUnit;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowLocalMarker(): bool
    {
        return $this->showLocalMarker;
    }

    /**
     * @psalm-mutation-free
     */
    public function isFocusLocalMarker(): bool
    {
        return $this->focusLocalMarker;
    }

    /**
     * @psalm-mutation-free
     */
    public function isForceAjaxSearch(): bool
    {
        return $this->forceAjaxSearch;
    }

    /**
     * @psalm-mutation-free
     */
    public function getSearchRadiusValues(): string
    {
        return $this->searchRadiusValues;
    }

    /**
     * @psalm-mutation-free
     */
    public function getDefaultSearchRadius(): int
    {
        return $this->defaultSearchRadius;
    }

    /**
     * @psalm-mutation-free
     */
    public function isFilterHighlightedStoresByRadiusFirst(): bool
    {
        return $this->filterHighlightedStoresByRadiusFirst;
    }

    /**
     * @psalm-mutation-free
     */
    public function isAutoloadStores(): bool
    {
        return $this->autoloadStores;
    }

    /**
     * @psalm-mutation-free
     */
    public function getSearchResultLimit(): int
    {
        return $this->searchResultLimit;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleMapIconSize(): string
    {
        return $this->googleMapIconSize;
    }

    /**
     * @psalm-mutation-free
     */
    public function isRouteOnGoogleMaps(): bool
    {
        return $this->routeOnGoogleMaps;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowCountryFilter(): bool
    {
        return $this->showCountryFilter;
    }

    /**
     * @psalm-mutation-free
     */
    public function getCountrySortBy(): string
    {
        return $this->countrySortBy;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleMapIcon(): ?string
    {
        return $this->googleMapIcon;
    }

    /**
     * @psalm-mutation-free
     */
    public function getPreselectedCountryId(): ?string
    {
        return $this->preselectedCountryId;
    }

    /**
     * @psalm-mutation-free
     */
    public function getTopCmsPage(): ?string
    {
        return $this->topCmsPage;
    }

    /**
     * @psalm-mutation-free
     */
    public function getBottomCmsPage(): ?string
    {
        return $this->bottomCmsPage;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowOpeningTimesOnListing(): bool
    {
        return $this->showOpeningTimesOnListing;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowOpeningTimesOnMarkerWindow(): bool
    {
        return $this->showOpeningTimesOnMarkerWindow;
    }

    /**
     * @psalm-mutation-free
     */
    public function isRequireCookieForGoogleMaps(): bool
    {
        return $this->requireCookieForGoogleMaps;
    }

    /**
     * @psalm-mutation-free
     */
    public function getBreadcrumbCategory(): ?string
    {
        return $this->breadcrumbCategory;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowIndividualFiltersInOffCanvas(): bool
    {
        return $this->showIndividualFiltersInOffCanvas;
    }

    /**
     * @psalm-mutation-free
     */
    public function getFilterTagMode(): string
    {
        return $this->filterTagMode;
    }

    /**
     * @psalm-mutation-free
     */
    public function isClearSearchOnCountryChange(): bool
    {
        return $this->clearSearchOnCountryChange;
    }

    /**
     * @psalm-mutation-free
     */
    public function isFilterShowAlways(): bool
    {
        return $this->filterShowAlways;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowStoresOnOrderFinish(): bool
    {
        return $this->showStoresOnOrderFinish;
    }

    /**
     * @psalm-mutation-free
     */
    public function getOrderFinishStoreCount(): int
    {
        return $this->orderFinishStoreCount;
    }

    /**
     * @psalm-mutation-free
     */
    public function getOrderFinishAddressType(): string
    {
        return $this->orderFinishAddressType;
    }

    /**
     * @psalm-mutation-free
     */
    public function getOrderFinishDisplayType(): string
    {
        return $this->orderFinishDisplayType;
    }

    /**
     * @psalm-mutation-free
     */
    public function getCookieLifetime(): int
    {
        return $this->cookieLifetime;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleMapConfig(): string
    {
        return $this->googleMapConfig;
    }

    /**
     * @psalm-mutation-free
     */
    public function getGoogleMapID(): string
    {
        return $this->googleMapID;
    }

    /**
     * @psalm-mutation-free
     */
    public function getAutocompleteResultPriority(): string
    {
        return $this->autocompleteResultPriority;
    }
}
