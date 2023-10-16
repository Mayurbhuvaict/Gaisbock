<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\Aggregate\ContactFormTranslation;

use NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\ContactFormEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ContactFormTranslationEntity extends TranslationEntity
{
    protected string             $netiStoreLocatorContactFormId = '';

    protected ?ContactFormEntity $netiStoreLocatorContactForm   = null;

    protected ?string            $label                         = null;

    protected ?string            $value                         = null;

    public function getNetiStoreLocatorContactFormId(): string
    {
        return $this->netiStoreLocatorContactFormId;
    }

    public function setNetiStoreLocatorContactFormId(string $netiStoreLocatorContactFormId): void
    {
        $this->netiStoreLocatorContactFormId = $netiStoreLocatorContactFormId;
    }

    public function getNetiStoreLocatorContactForm(): ?ContactFormEntity
    {
        return $this->netiStoreLocatorContactForm;
    }

    public function setNetiStoreLocatorContactForm(?ContactFormEntity $netiStoreLocatorContactForm): void
    {
        $this->netiStoreLocatorContactForm = $netiStoreLocatorContactForm;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
