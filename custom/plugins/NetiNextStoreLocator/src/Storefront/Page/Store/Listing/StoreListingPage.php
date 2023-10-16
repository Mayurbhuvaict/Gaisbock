<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Page\Store\Listing;

use Shopware\Storefront\Page\Page;

class StoreListingPage extends Page
{
    private array   $countries             = [];

    private array   $radiusList            = [];

    private array   $contactFormFields     = [];

    private array   $config                = [];

    private array   $contactSubjectOptions = [];

    private array   $orderTypes            = [];

    private ?string $topCmsPageHtml        = null;

    private ?string $bottomCmsPageHtml     = null;

    private array   $filters               = [];

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): void
    {
        $this->countries = $countries;
    }

    public function getRadiusList(): array
    {
        return $this->radiusList;
    }

    public function setRadiusList(array $radiusList): void
    {
        $this->radiusList = $radiusList;
    }

    public function getContactFormFields(): array
    {
        return $this->contactFormFields;
    }

    public function setContactFormFields(array $contactFormFields): void
    {
        $this->contactFormFields = $contactFormFields;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getContactSubjectOptions(): array
    {
        return $this->contactSubjectOptions;
    }

    public function setContactSubjectOptions(array $contactSubjectOptions): void
    {
        $this->contactSubjectOptions = $contactSubjectOptions;
    }

    public function getOrderTypes(): array
    {
        return $this->orderTypes;
    }

    public function setOrderTypes(array $orderTypes): void
    {
        $this->orderTypes = $orderTypes;
    }

    public function getTopCmsPageHtml(): ?string
    {
        return $this->topCmsPageHtml;
    }

    public function setTopCmsPageHtml(?string $topCmsPageHtml): void
    {
        $this->topCmsPageHtml = $topCmsPageHtml;
    }

    public function getBottomCmsPageHtml(): ?string
    {
        return $this->bottomCmsPageHtml;
    }

    public function setBottomCmsPageHtml(?string $bottomCmsPageHtml): void
    {
        $this->bottomCmsPageHtml = $bottomCmsPageHtml;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }
}
