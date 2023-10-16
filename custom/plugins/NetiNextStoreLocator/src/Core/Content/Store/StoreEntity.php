<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Store;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\Aggregate\StoreTranslation\StoreTranslationCollection;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreCms\StoreCmsCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateEntity;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\SalesChannel\SalesChannelCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class StoreEntity extends Entity
{
    use EntityIdTrait;

    final public const CONTENT_TYPE_HTML = 'html';

    final public const CONTENT_TYPE_CMS  = 'cms';

    protected string                     $label                   = '';

    protected ?string                    $description             = null;

    protected string                     $street                  = '';

    protected ?string                    $streetNumber            = null;

    protected string                     $zipCode                 = '';

    protected string                     $city                    = '';

    protected ?float                     $longitude               = null;

    protected ?float                     $latitude                = null;

    protected ?string                    $timezone                = null;

    protected ?string                    $phone                   = null;

    protected ?string                    $fax                     = null;

    protected ?string                    $url                     = null;

    protected ?string                    $email                   = null;

    protected ?string                    $additionalInformation   = null;

    protected ?string                    $countryId               = null;

    protected ?CountryEntity             $country                 = null;

    protected ?string                    $countryStateId          = null;

    protected ?CountryStateEntity        $countryState            = null;

    protected ?bool                      $active                  = null;

    protected ?bool                      $contactFormEnabled      = null;

    protected ?bool                      $contactFormDetail       = null;

    protected ?bool                      $hidden                  = null;

    protected ?string                    $notificationMailAddress = '';

    protected string                     $showAlways              = '';

    protected int                        $zoom                    = 0;

    protected ?bool                      $excludeFromSync         = null;

    protected ?string                    $googlePlaceID           = null;

    protected ?bool                      $featured                = null;

    protected ?int                       $radius                  = null;

    protected ?bool                      $detailPageEnabled       = null;

    protected ?string                    $pictureMediaId          = null;

    protected ?MediaEntity               $pictureMedia            = null;

    protected ?string                    $iconMediaId             = null;

    protected ?MediaEntity               $iconMedia               = null;

    protected ?string                    $detailContentType       = null;

    protected ?array                     $customFields            = null;

    protected ?string                    $externalId              = null;

    protected ?string                    $detailsPictureMediaId   = null;

    protected ?MediaEntity               $detailsPictureMedia     = null;

    protected SalesChannelCollection     $salesChannels;

    protected StoreTranslationCollection $translations;

    protected StoreCmsCollection         $cmsPages;

    public function __construct()
    {
        $this->salesChannels = new SalesChannelCollection();
        $this->translations  = new StoreTranslationCollection();
        $this->cmsPages      = new StoreCmsCollection();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): void
    {
        $this->fax = $fax;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): void
    {
        $this->additionalInformation = $additionalInformation;
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

    public function getCountryStateId(): ?string
    {
        return $this->countryStateId;
    }

    public function setCountryStateId(?string $countryStateId): void
    {
        $this->countryStateId = $countryStateId;
    }

    public function getCountryState(): ?CountryStateEntity
    {
        return $this->countryState;
    }

    public function setCountryState(?CountryStateEntity $countryState): void
    {
        $this->countryState = $countryState;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getContactFormEnabled(): ?bool
    {
        return $this->contactFormEnabled;
    }

    public function setContactFormEnabled(?bool $contactFormEnabled): void
    {
        $this->contactFormEnabled = $contactFormEnabled;
    }

    public function getContactFormDetail(): ?bool
    {
        return $this->contactFormDetail;
    }

    public function setContactFormDetail(?bool $contactFormDetail): void
    {
        $this->contactFormDetail = $contactFormDetail;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(?bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getNotificationMailAddress(): ?string
    {
        return $this->notificationMailAddress;
    }

    public function setNotificationMailAddress(?string $notificationMailAddress): void
    {
        $this->notificationMailAddress = $notificationMailAddress;
    }

    public function getShowAlways(): string
    {
        return $this->showAlways;
    }

    public function setShowAlways(string $showAlways): void
    {
        $this->showAlways = $showAlways;
    }

    public function getZoom(): int
    {
        return $this->zoom;
    }

    public function setZoom(int $zoom): void
    {
        $this->zoom = $zoom;
    }

    public function getExcludeFromSync(): ?bool
    {
        return $this->excludeFromSync;
    }

    public function setExcludeFromSync(?bool $excludeFromSync): void
    {
        $this->excludeFromSync = $excludeFromSync;
    }

    public function getGooglePlaceID(): ?string
    {
        return $this->googlePlaceID;
    }

    public function setGooglePlaceID(?string $googlePlaceID): void
    {
        $this->googlePlaceID = $googlePlaceID;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(?bool $featured): void
    {
        $this->featured = $featured;
    }

    public function getRadius(): ?int
    {
        return $this->radius;
    }

    public function setRadius(?int $radius): void
    {
        $this->radius = $radius;
    }

    public function getDetailPageEnabled(): ?bool
    {
        return $this->detailPageEnabled;
    }

    public function setDetailPageEnabled(?bool $detailPageEnabled): void
    {
        $this->detailPageEnabled = $detailPageEnabled;
    }

    public function getSalesChannels(): SalesChannelCollection
    {
        return $this->salesChannels;
    }

    public function setSalesChannels(SalesChannelCollection $salesChannels): void
    {
        $this->salesChannels = $salesChannels;
    }

    public function getPictureMediaId(): ?string
    {
        return $this->pictureMediaId;
    }

    public function setPictureMediaId(?string $pictureMediaId): void
    {
        $this->pictureMediaId = $pictureMediaId;
    }

    public function getPictureMedia(): ?MediaEntity
    {
        return $this->pictureMedia;
    }

    public function setPictureMedia(?MediaEntity $pictureMedia): void
    {
        $this->pictureMedia = $pictureMedia;
    }

    public function getIconMediaId(): ?string
    {
        return $this->iconMediaId;
    }

    public function setIconMediaId(?string $iconMediaId): void
    {
        $this->iconMediaId = $iconMediaId;
    }

    public function getIconMedia(): ?MediaEntity
    {
        return $this->iconMedia;
    }

    public function setIconMedia(?MediaEntity $iconMedia): void
    {
        $this->iconMedia = $iconMedia;
    }

    public function getTranslations(): StoreTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(StoreTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getDetailContentType(): ?string
    {
        return $this->detailContentType;
    }

    public function setDetailContentType(?string $detailContentType): void
    {
        $this->detailContentType = $detailContentType;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getDetailsPictureMediaId(): ?string
    {
        return $this->detailsPictureMediaId;
    }

    public function setDetailsPictureMediaId(?string $detailsPictureMediaId): void
    {
        $this->detailsPictureMediaId = $detailsPictureMediaId;
    }

    public function getDetailsPictureMedia(): ?MediaEntity
    {
        return $this->detailsPictureMedia;
    }

    public function setDetailsPictureMedia(?MediaEntity $detailsPictureMedia): void
    {
        $this->detailsPictureMedia = $detailsPictureMedia;
    }

    public function getCmsPages(): StoreCmsCollection
    {
        return $this->cmsPages;
    }

    public function setCmsPages(StoreCmsCollection $cmsPages): void
    {
        $this->cmsPages = $cmsPages;
    }
}
