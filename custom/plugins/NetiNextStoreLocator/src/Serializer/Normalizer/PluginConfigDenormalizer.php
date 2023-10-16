<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Serializer\Normalizer;

use NetInventors\NetiNextStoreLocator\Struct\PluginConfigStruct;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use function is_array;

class PluginConfigDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = []): PluginConfigStruct
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException();
        }

        $denormalizedObject = new PluginConfigStruct();

        $closureFn = \Closure::bind(
            /** @psalm-suppress InaccessibleProperty */
            static function (PluginConfigStruct $denormalizedObject) use ($data): void {
                if (isset($data['active'])) {
                    $denormalizedObject->active = (bool) $data['active'];
                }

                if (isset($data['seoUrl'])) {
                    $denormalizedObject->seoUrl = (string) $data['seoUrl'];
                }

                if (isset($data['detailPage'])) {
                    $denormalizedObject->detailPage = (bool) $data['detailPage'];
                }

                if (isset($data['googleApiKey'])) {
                    $denormalizedObject->googleApiKey = (string) $data['googleApiKey'];
                }

                if (isset($data['googleWorkApiKey'])) {
                    $denormalizedObject->googleWorkApiKey = (string) $data['googleWorkApiKey'];
                }

                if (isset($data['googleApiLang'])) {
                    $denormalizedObject->googleApiLang = (string) $data['googleApiLang'];
                }

                if (isset($data['googleApiLangInheritFromDomain'])) {
                    $denormalizedObject->googleApiLangInheritFromDomain = (bool) $data['googleApiLangInheritFromDomain'];
                }

                if (isset($data['googleApiTimezoneEnabled'])) {
                    $denormalizedObject->googleApiTimezoneEnabled = (bool) $data['googleApiTimezoneEnabled'];
                }

                if (isset($data['getGeolocation'])) {
                    $denormalizedObject->getGeolocation = (string) $data['getGeolocation'];
                }

                if (isset($data['googleMarkerClustering'])) {
                    $denormalizedObject->googleMarkerClustering = (bool) $data['googleMarkerClustering'];
                }

                if (isset($data['contactSubjectOptions'])) {
                    $denormalizedObject->contactSubjectOptions = (string) $data['contactSubjectOptions'];
                }

                if (isset($data['googleMapOptions'])) {
                    $denormalizedObject->googleMapOptions = (string) $data['googleMapOptions'];
                }

                if (isset($data['restrictFeaturedStoresToCountry'])) {
                    $denormalizedObject->restrictFeaturedStoresToCountry =
                        (bool) $data['restrictFeaturedStoresToCountry'];
                }

                if (isset($data['showLocalMarker'])) {
                    $denormalizedObject->showLocalMarker = (bool) $data['showLocalMarker'];
                }

                if (isset($data['focusLocalMarker'])) {
                    $denormalizedObject->focusLocalMarker = (bool) $data['focusLocalMarker'];
                }

                if (isset($data['distanceUnit'])) {
                    $denormalizedObject->distanceUnit = (string) $data['distanceUnit'];
                }

                if (isset($data['forceAjaxSearch'])) {
                    $denormalizedObject->forceAjaxSearch = (bool) $data['forceAjaxSearch'];
                }

                if (isset($data['searchRadiusValues'])) {
                    $denormalizedObject->searchRadiusValues = (string) $data['searchRadiusValues'];
                }

                if (isset($data['defaultSearchRadius'])) {
                    $denormalizedObject->defaultSearchRadius = (int) $data['defaultSearchRadius'];
                }

                if (isset($data['filterHighlightedStoresByRadiusFirst'])) {
                    $denormalizedObject->filterHighlightedStoresByRadiusFirst = (bool) $data['filterHighlightedStoresByRadiusFirst'];
                }

                if (isset($data['autoloadStores'])) {
                    $denormalizedObject->autoloadStores = (bool) $data['autoloadStores'];
                }

                if (isset($data['searchResultLimit'])) {
                    $denormalizedObject->searchResultLimit = (int) $data['searchResultLimit'];
                }

                if (isset($data['googleMapIconSize'])) {
                    $denormalizedObject->googleMapIconSize = (string) $data['googleMapIconSize'];
                }

                if (isset($data['routeOnGoogleMaps'])) {
                    $denormalizedObject->routeOnGoogleMaps = (bool) $data['routeOnGoogleMaps'];
                }

                if (isset($data['showCountryFilter'])) {
                    $denormalizedObject->showCountryFilter = (bool) $data['showCountryFilter'];
                }

                if (isset($data['countrySortBy'])) {
                    $denormalizedObject->countrySortBy = (string) $data['countrySortBy'];
                }

                if (isset($data['googleMapIcon'])) {
                    $denormalizedObject->googleMapIcon = (string) $data['googleMapIcon'];
                }

                if (isset($data['preselectedCountryId'])) {
                    $denormalizedObject->preselectedCountryId = (string) $data['preselectedCountryId'];
                }

                if (isset($data['topCmsPage'])) {
                    $denormalizedObject->topCmsPage = (string) $data['topCmsPage'];
                }

                if (isset($data['bottomCmsPage'])) {
                    $denormalizedObject->bottomCmsPage = (string) $data['bottomCmsPage'];
                }

                if (isset($data['showOpeningTimesOnListing'])) {
                    $denormalizedObject->showOpeningTimesOnListing = (bool) $data['showOpeningTimesOnListing'];
                }

                if (isset($data['showOpeningTimesOnMarkerWindow'])) {
                    $denormalizedObject->showOpeningTimesOnMarkerWindow = (bool) $data['showOpeningTimesOnMarkerWindow'];
                }

                if (isset($data['requireCookieForGoogleMaps'])) {
                    $denormalizedObject->requireCookieForGoogleMaps = (bool) $data['requireCookieForGoogleMaps'];
                }

                if (isset($data['breadcrumbCategory'])) {
                    $denormalizedObject->breadcrumbCategory = (string) $data['breadcrumbCategory'];
                }

                if (isset($data['showIndividualFiltersInOffCanvas'])) {
                    $denormalizedObject->showIndividualFiltersInOffCanvas = (bool) $data['showIndividualFiltersInOffCanvas'];
                }

                if (isset($data['filterTagMode'])) {
                    $denormalizedObject->filterTagMode = (string) $data['filterTagMode'];
                }

                if (isset($data['clearSearchOnCountryChange'])) {
                    $denormalizedObject->clearSearchOnCountryChange = (bool) $data['clearSearchOnCountryChange'];
                }

                if (isset($data['filterShowAlways'])) {
                    $denormalizedObject->filterShowAlways = (bool) $data['filterShowAlways'];
                }

                if (isset($data['showStoresOnOrderFinish'])) {
                    $denormalizedObject->showStoresOnOrderFinish = (bool) $data['showStoresOnOrderFinish'];
                }

                if (isset($data['orderFinishStoreCount'])) {
                    $denormalizedObject->orderFinishStoreCount = (int) $data['orderFinishStoreCount'];
                }

                if (isset($data['orderFinishAddressType'])) {
                    $denormalizedObject->orderFinishAddressType = (string) $data['orderFinishAddressType'];
                }

                if (isset($data['orderFinishDisplayType'])) {
                    $denormalizedObject->orderFinishDisplayType = (string) $data['orderFinishDisplayType'];
                }

                if (isset($data['cookieLifetime'])) {
                    $denormalizedObject->cookieLifetime = (int) $data['cookieLifetime'];
                }

                if (isset($data['googleMapConfig'])) {
                    $denormalizedObject->googleMapConfig = (string) $data['googleMapConfig'];
                }

                if (isset($data['googleMapID'])) {
                    $denormalizedObject->googleMapID = (string) $data['googleMapID'];
                }

                if (isset($data['autocompleteResultPriority'])) {
                    $denormalizedObject->autocompleteResultPriority = (string) $data['autocompleteResultPriority'];
                }
            },
            null,
            PluginConfigStruct::class,
        );

        if (!$closureFn) {
            throw new RuntimeException();
        }

        $closureFn($denormalizedObject);

        return $denormalizedObject;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return PluginConfigStruct::class === $type;
    }
}
