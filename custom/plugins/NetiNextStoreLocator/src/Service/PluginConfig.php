<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service;

use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;

/**
 * @deprecated Use \NetInventors\NetiNextStoreLocator\Service\PluginConfigFactory instead or inject
 *             neti_store_locator.plugin_config
 */
class PluginConfig
{
    final public const CONFIG_DOMAIN                                     = PluginConfigFactory::CONFIG_DOMAIN;

    final public const COUNTRY_SORT_BY_NAME_ASC                          = PluginConfigStruct::COUNTRY_SORT_BY_NAME_ASC;

    final public const COUNTRY_SORT_BY_NAME_DESC                         = PluginConfigStruct::COUNTRY_SORT_BY_NAME_DESC;

    final public const COUNTRY_SORT_BY_POSITION_ASC                      = PluginConfigStruct::COUNTRY_SORT_BY_POSITION_ASC;

    final public const COUNTRY_SORT_BY_POSITION_DESC                     = PluginConfigStruct::COUNTRY_SORT_BY_POSITION_DESC;

    final public const ORDER_FINISH_ADDRESS_TYPE_SHIPPING                = PluginConfigStruct::ORDER_FINISH_ADDRESS_TYPE_SHIPPING;

    final public const ORDER_FINISH_ADDRESS_TYPE_BILLING                 = PluginConfigStruct::ORDER_FINISH_ADDRESS_TYPE_BILLING;

    final public const ORDER_FINISH_DISPLAY_TYPE_LIST                    = PluginConfigStruct::ORDER_FINISH_DISPLAY_TYPE_LIST;

    final public const AUTOCOMPLETE_RESULT_PRIORITY_PLACES_BEFORE_STORES = PluginConfigStruct::AUTOCOMPLETE_RESULT_PRIORITY_PLACES_BEFORE_STORES;

    final public const AUTOCOMPLETE_RESULT_PRIORITY_STORES_BEFORE_PLACES = PluginConfigStruct::AUTOCOMPLETE_RESULT_PRIORITY_STORES_BEFORE_PLACES;

    public function __construct(
        private readonly PluginConfigFactory $pluginConfigFactory
    ) {
    }

    private function getConfig(): PluginConfigStruct
    {
        return $this->pluginConfigFactory->create();
    }

    public function isActive(): bool
    {
        return $this->getConfig()->isActive();
    }

    public function getSeoUrl(): string
    {
        return $this->getConfig()->getSeoUrl();
    }

    public function isDetailPageEnabled(): bool
    {
        return $this->getConfig()->isDetailPage();
    }

    public function getGoogleApiKey(): string
    {
        return $this->getConfig()->getGoogleApiKey();
    }

    public function getGoogleWorkApiKey(): string
    {
        return $this->getConfig()->getGoogleWorkApiKey();
    }

    public function getGoogleApiLanguage(): ?string
    {
        return $this->getConfig()->getGoogleApiLang();
    }

    public function isGoogleTimezoneApiEnabled(): bool
    {
        return $this->getConfig()->isGoogleApiTimezoneEnabled();
    }

    public function isGeoLocationEnabled(): string
    {
        return $this->getConfig()->getGetGeolocation();
    }

    public function isGoogleMarkerClusteringEnabled(): bool
    {
        return $this->getConfig()->isGoogleMarkerClustering();
    }

    public function getContactSubjectOptions(): string
    {
        return $this->getConfig()->getContactSubjectOptions();
    }

    public function getGoogleMapOptions(): string
    {
        return $this->getConfig()->getGoogleMapOptions();
    }

    public function isRestrictionFeaturedStoresToCountryEnabled(): bool
    {
        return $this->getConfig()->isRestrictFeaturedStoresToCountry();
    }

    public function getDistanceUnit(): string
    {
        return $this->getConfig()->getDistanceUnit();
    }

    public function isForceAjaxSearch(): bool
    {
        return $this->getConfig()->isForceAjaxSearch();
    }

    public function getSearchRadiusValues(): string
    {
        return $this->getConfig()->getSearchRadiusValues();
    }

    public function getDefaultSearchRadius(): int
    {
        return $this->getConfig()->getDefaultSearchRadius();
    }

    public function isAutoloadStores(): bool
    {
        return $this->getConfig()->isAutoloadStores();
    }

    public function getSearchResultLimit(): int
    {
        return $this->getConfig()->getSearchResultLimit();
    }

    public function getGoogleMapIconSize(): string
    {
        return $this->getConfig()->getGoogleMapIconSize();
    }

    public function isRouteOnGoogleMapsEnabled(): bool
    {
        return $this->getConfig()->isRouteOnGoogleMaps();
    }

    public function isShowCountryFilter(): bool
    {
        return $this->getConfig()->isShowCountryFilter();
    }

    public function getCountrySortBy(): string
    {
        return $this->getConfig()->getCountrySortBy();
    }

    public function getGoogleMapIcon(): ?string
    {
        return $this->getConfig()->getGoogleMapIcon();
    }

    public function getPreselectedCountryId(): ?string
    {
        return $this->getConfig()->getPreselectedCountryId();
    }

    public function getTopCmsPageId(): ?string
    {
        return $this->getConfig()->getTopCmsPage();
    }

    public function getBottomCmsPageId(): ?string
    {
        return $this->getConfig()->getBottomCmsPage();
    }

    public function isShowOpeningTimesOnListingEnabled(): bool
    {
        return $this->getConfig()->isShowOpeningTimesOnListing();
    }

    public function isShowOpeningTimesOnMarkerWindowEnabled(): bool
    {
        return $this->getConfig()->isShowOpeningTimesOnMarkerWindow();
    }

    public function isCookieForGoogleMapsRequired(): bool
    {
        return $this->getConfig()->isRequireCookieForGoogleMaps();
    }

    public function getBreadcrumbCategoryId(): ?string
    {
        return $this->getConfig()->getBreadcrumbCategory();
    }

    public function isShowIndividualFiltersInOffCanvas(): bool
    {
        return $this->getConfig()->isShowIndividualFiltersInOffCanvas();
    }

    public function getFilterTagMode(): string
    {
        return $this->getConfig()->getFilterTagMode();
    }

    public function isClearSearchOnCountryChange(): bool
    {
        return $this->getConfig()->isClearSearchOnCountryChange();
    }

    public function isFilterShowAlways(): bool
    {
        return $this->getConfig()->isFilterShowAlways();
    }

    public function isShowStoresOnOrderFinish(): bool
    {
        return $this->getConfig()->isShowStoresOnOrderFinish();
    }

    public function getOrderFinishStoreCount(): int
    {
        return $this->getConfig()->getOrderFinishStoreCount();
    }

    public function getOrderFinishAddressType(): string
    {
        return $this->getConfig()->getOrderFinishAddressType();
    }

    public function getOrderFinishDisplayType(): string
    {
        return $this->getConfig()->getOrderFinishDisplayType();
    }

    public function getCookieLifetime(): int
    {
        return $this->getConfig()->getCookieLifetime();
    }

    public function getGoogleMapConfig(): string
    {
        return $this->getConfig()->getGoogleMapConfig();
    }

    public function getGoogleMapID(): string
    {
        return $this->getConfig()->getGoogleMapID();
    }

    public function getAutocompleteResultPriority(): string
    {
        return $this->getConfig()->getAutocompleteResultPriority();
    }
}
