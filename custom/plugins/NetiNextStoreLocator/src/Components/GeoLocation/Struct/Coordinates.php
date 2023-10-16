<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\GeoLocation\Struct;

use Shopware\Core\Framework\Struct\Struct;

class Coordinates extends Struct
{
    public function __construct(
        protected float  $latitude = 0.0,
        protected float  $longitude = 0.0,
        protected ?string $placeId = null
    ) {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    public function setPlaceId(?string $placeId): void
    {
        $this->placeId = $placeId;
    }
}
