<?php declare(strict_types=1);

namespace Huebert\SeoFaq\Core\Content\SeoFaqQuestions\Aggregate\SeoFaqQuestionsTranslation;

use Huebert\SeoFaq\Core\Content\SeoFaqQuestions\SeoFaqQuestionsEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class SeoFaqQuestionsTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $seoFaqQuestionsId;

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
     * @var SeoFaqQuestionsEntity|null
     */
    protected $seoFaqQuestions;

    /**
     * @return string
     */
    public function getSeoFaqQuestionsId(): string
    {
        return $this->seoFaqQuestionsId;
    }

    /**
     * @param string $seoFaqQuestionsId
     */
    public function setSeoFaqQuestionsId(string $seoFaqQuestionsId): void
    {
        $this->seoFaqQuestionsId = $seoFaqQuestionsId;
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
     * @return SeoFaqQuestionsEntity|null
     */
    public function getSeoFaqQuestions(): ?SeoFaqQuestionsEntity
    {
        return $this->seoFaqQuestions;
    }

    /**
     * @param SeoFaqQuestionsEntity|null $seoFaqQuestions
     */
    public function setSeoFaqQuestions(?SeoFaqQuestionsEntity $seoFaqQuestions): void
    {
        $this->seoFaqQuestions = $seoFaqQuestions;
    }
}

