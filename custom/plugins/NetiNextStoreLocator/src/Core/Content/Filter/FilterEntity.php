<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Filter;

use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterTranslation\FilterTranslationCollection;
use NetInventors\NetiNextStoreLocator\Core\Content\Filter\Aggregate\FilterValue\FilterValueCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\CustomField\CustomFieldEntity;
use Shopware\Core\System\Tag\TagCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilterEntity extends Entity
{
    use EntityIdTrait;

    final public const VALUE_TYPE_TAG          = 1;

    final public const VALUE_TYPE_CUSTOM_FIELD = 2;

    final public const DISPLAY_TYPE_CHECKBOX   = 1;

    final public const DISPLAY_TYPE_RADIO      = 2;

    final public const DISPLAY_TYPE_SELECT     = 3;

    protected bool                        $active        = false;

    protected int                         $valueType     = self::VALUE_TYPE_TAG;

    protected int                         $displayType   = self::DISPLAY_TYPE_CHECKBOX;

    protected int                         $position      = 0;

    protected ?string                     $title         = null;

    protected ?string                     $customFieldId = null;

    protected ?CustomFieldEntity          $customField   = null;

    protected TagCollection               $tags;

    protected FilterTranslationCollection $translations;

    protected FilterValueCollection       $values;

    public function __construct()
    {
        $this->tags         = new TagCollection();
        $this->translations = new FilterTranslationCollection();
        $this->values       = new FilterValueCollection();
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getValueType(): int
    {
        return $this->valueType;
    }

    public function setValueType(int $valueType): void
    {
        $this->valueType = $valueType;
    }

    public function getDisplayType(): int
    {
        return $this->displayType;
    }

    public function setDisplayType(int $displayType): void
    {
        $this->displayType = $displayType;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    public function setTags(TagCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function getCustomFieldId(): ?string
    {
        return $this->customFieldId;
    }

    public function setCustomFieldId(?string $customFieldId): void
    {
        $this->customFieldId = $customFieldId;
    }

    public function getCustomField(): ?CustomFieldEntity
    {
        return $this->customField;
    }

    public function setCustomField(?CustomFieldEntity $customField): void
    {
        $this->customField = $customField;
    }

    public function getTranslations(): FilterTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(FilterTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getValues(): FilterValueCollection
    {
        return $this->values;
    }

    public function setValues(FilterValueCollection $values): void
    {
        $this->values = $values;
    }
}
