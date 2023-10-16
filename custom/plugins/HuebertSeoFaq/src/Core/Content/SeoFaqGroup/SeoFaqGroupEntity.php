<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqGroup;

use Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation\SeoFaqGroupTranslationCollection;
use Huebert\SeoFaq\Core\Content\SeoFaqGroup\Aggregate\SeoFaqGroupTranslation\SeoFaqGroupTranslationDefinition;
use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation\SeoFaqQuestionsTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class SeoFaqGroupEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */

    protected $active;
    /**
     * @var string
     */
    protected $nameOld;

    /**
     * @var SeoFaqQuestionsTranslationCollection|null
     */
    protected $translations;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getOldName(): ?string
    {
        return $this->nameOld;
    }

    /**
     * @param string $nameOld
     */
    public function setOldName(string $nameOld): void
    {
        $this->nameOld = $nameOld;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return SeoFaqGroupTranslationCollection|null
     */
    public function getTranslations(): ?SeoFaqGroupTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param SeoFaqGroupTranslationCollection|null $translations
     */
    public function setTranslations(?SeoFaqGroupTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }
}
