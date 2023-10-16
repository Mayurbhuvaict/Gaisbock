<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\ContactForm;

use NetInventors\NetiNextStoreLocator\Core\Content\ContactForm\Aggregate\ContactFormTranslation\ContactFormTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ContactFormEntity extends Entity
{
    use EntityIdTrait;

    protected bool                             $active   = false;

    protected string                           $label    = '';

    protected string                           $type     = '';

    protected string                           $value    = '';

    protected bool                             $required = false;

    protected int                              $position = 0;

    protected ContactFormTranslationCollection $translations;

    public function __construct()
    {
        $this->translations = new ContactFormTranslationCollection();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getTranslations(): ContactFormTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(ContactFormTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }
}
