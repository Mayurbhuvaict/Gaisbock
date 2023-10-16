<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\GeoLocation;

use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Address;
use NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct\Coordinates;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class GeoLocation
{
    protected const GEOCODE_API_URL  = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected const TIMEZONE_API_URL = 'https://maps.googleapis.com/maps/api/timezone/json';

    private readonly ?string $apiKey;

    private readonly bool    $timezoneApiEnabled;

    /**
     * @var array<string, CountryEntity|null>
     */
    private array $countryCache     = [];

    private bool  $hasTimezoneError = false;

    public function __construct(
        SystemConfigService               $systemConfigService,
        private readonly EntityRepository $countryRepository,
        private readonly LoggerInterface  $logger
    ) {
        /** @var string|null $apiKey */
        $apiKey = $systemConfigService->get('NetiNextStoreLocator.config.googleWorkApiKey');

        $this->apiKey             = $apiKey;
        $this->timezoneApiEnabled =
            $systemConfigService->get('NetiNextStoreLocator.config.googleApiTimezoneEnabled') === true;
    }

    public function hasTimezoneError(): bool
    {
        return $this->hasTimezoneError;
    }

    public function getTimezone(Coordinates $coordinates): ?string
    {
        // The method simply returns null when the timezone is not enabled to minimize invalid requests to the api.
        if (false === $this->timezoneApiEnabled || null === $this->apiKey) {
            return null;
        }

        $url = sprintf(
            '%s?location=%s&timestamp=%s&key=%s',
            self::TIMEZONE_API_URL,
            $coordinates->getLatitude() . ',' . $coordinates->getLongitude(),
            time(),
            $this->apiKey
        );

        $json = file_get_contents($url);

        /** @var array|false $data */
        $data = json_decode($json, true);

        if (is_array($data)) {
            /** @var string|null $status */
            $status = $data['status'] ?? null;

            if ('OK' === $status && isset($data['timeZoneId']) && is_string($data['timeZoneId'])) {
                return $data['timeZoneId'];
            }

            $this->logger->warning(
                'Unable to get the timezone by coordinates',
                [
                    'status'  => $status,
                    'message' => $data['errorMessage'] ?? null,
                ]
            );

            $this->hasTimezoneError = true;
        }

        return null;
    }

    /**
     * Asks Google for the coordinates of a certain address.
     *
     *
     * @throws InconsistentCriteriaIdsException|\Exception
     */
    public function getCoords(Address $address): Coordinates
    {
        if (null === $this->apiKey) {
            throw new \Exception('No api key configured. Please setup the plugin first.');
        }

        $url = sprintf(
            '%s?address=%s&key=%s',
            self::GEOCODE_API_URL,
            $this->urlEncodeAddress($address),
            $this->apiKey
        );

        $json = file_get_contents($url);

        /**
         * @var array{
         *      status: string,
         *      results: array{
         *          place_id: string,
         *          geometry: array{
         *              location: array{
         *                 lat: float,
         *                 lng: float
         *              }
         *          }
         *      }[]
         * } $data
         */
        $data = (array) json_decode($json, true);

        switch ($data['status']) {
            case 'OK':
                $coords = new Coordinates();
                $coords->setLatitude($data['results'][0]['geometry']['location']['lat']);
                $coords->setLongitude($data['results'][0]['geometry']['location']['lng']);
                $coords->setPlaceId($data['results'][0]['place_id']);

                return $coords;
            default:
                throw new \Exception(
                    'Unable to locate the address "' . ((string) $address) . '" (' . $data['status'] . ')'
                );
        }
    }

    /**
     * Converts an address struct into a readable string for Google.
     *
     *
     */
    private function urlEncodeAddress(Address $address): string
    {
        $address->setCountry($this->getCountryById($address->getCountryId()));

        return urlencode((string) $address);
    }

    /**
     * Gets a country entity by id. The result is cached for further calls.
     *
     *
     * @throws InconsistentCriteriaIdsException
     */
    private function getCountryById(?string $countryId): ?CountryEntity
    {
        if (
            is_string($countryId)
            && Uuid::isValid($countryId)
            && false === array_key_exists($countryId, $this->countryCache)
        ) {
            $criteria = new Criteria();
            $context  = new Context(new SystemSource());

            $criteria->addFilter(new EqualsFilter('id', $countryId));

            $result = $this->countryRepository->search($criteria, $context);
            /** @var CountryEntity|null $country */
            $country = $result->first();

            $this->countryCache[$countryId] = $country;
        }

        $country = $this->countryCache[$countryId] ?? null;

        if ($country instanceof CountryEntity) {
            return $country;
        }

        return null;
    }
}
