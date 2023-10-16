<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions;

use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation\SeoFaqQuestionsTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class SeoFaqQuestionsEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string|null
     */
    protected $group;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $question;

    /**
     * @var string|null
     */
    protected $answer;

    /**
     * @var string|null
     */
    protected $metaTitle;

    /**
     * @var string|null
     */
    protected $metaDescription;

    /**
     * @var string|null
     */
    protected $keywords;

    /**
     * @var SeoFaqQuestionsTranslationCollection|null
     */
    protected $translations;

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
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     */
    public function setGroup(?string $group): void
    {
        $this->group = $group;
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
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string|null $question
     */
    public function setQuestion(?string $question): void
    {
        $this->question = $question;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @param string|null $answer
     */
    public function setAnswer(?string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param string|null $metaTitle
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string|null $metaDescription
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string|null $keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return SeoFaqQuestionsTranslationCollection|null
     */
    public function getTranslations(): ?SeoFaqQuestionsTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param SeoFaqQuestionsTranslationCollection|null $translations
     */
    public function setTranslations(?SeoFaqQuestionsTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }


}
