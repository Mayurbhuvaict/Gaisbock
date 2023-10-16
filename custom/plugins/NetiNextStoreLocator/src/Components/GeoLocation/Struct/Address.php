<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct;

use Shopware\Core\Framework\Struct\Struct;
use Shopware\Core\System\Country\CountryEntity;

class Address extends Struct implements \Stringable
{
    protected string         $street       = '';

    protected ?string        $streetNumber = null;

    protected string         $zipCode      = '';

    protected string         $city         = '';

    protected ?string        $countryId    = null;

    protected ?CountryEntity $country      = null;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountryId(): ?string
    {
        return $this->countryId;
    }

    public function setCountryId(?string $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    public function setCountry(?CountryEntity $country): void
    {
        $this->country = $country;
    }

    public function __toString(): string
    {
        $countryName = '';

        if ($this->country) {
            $countryName = (string) $this->country->getTranslation('name');
            $countryName = ', ' . $countryName;
        }

        return sprintf(
            '%s %s, %s %s%s',
            $this->getStreet(),
            $this->getStreetNumber() ?? '',
            $this->getZipCode(),
            $this->getCity(),
            $countryName
        );
    }
}
