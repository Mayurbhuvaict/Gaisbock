<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article;


use Pluszwei\FaqManager\Core\Content\Article\Translation\ArticleTranslationCollection;
use Pluszwei\FaqManager\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Seo\SeoUrl\SeoUrlCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelCollection;

class ArticleEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $featured;

    /**
     * @var string
     */
    protected $categoryId;

    /**
     * @var CategoryEntity
     */
    protected $category;

    /**
     * @var string
     */
    protected $sectionId;

    /**
     * @var CategoryEntity
     */
    protected $section;

    /**
     * @var SeoUrlCollection|null
     */
    protected $seoUrls;

    /**
     * @var ArticleTranslationCollection|null
     */
    protected $translations;

    /**
     * @var SalesChannelCollection|null
     */
    protected $salesChannels;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param  bool  $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * @param  bool  $featured
     */
    public function setFeatured(bool $featured): void
    {
        $this->featured = $featured;
    }

    /**
     * @return ArticleTranslationCollection|null
     */
    public function getTranslations(): ?ArticleTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param  ArticleTranslationCollection|null  $translations
     */
    public function setTranslations(?ArticleTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * @return SeoUrlCollection|null
     */
    public function getSeoUrls(): ?SeoUrlCollection
    {
        return $this->seoUrls;
    }

    /**
     * @param  SeoUrlCollection|null  $seoUrls
     */
    public function setSeoUrls(?SeoUrlCollection $seoUrls): void
    {
        $this->seoUrls = $seoUrls;
    }

    /**
     * @return string|null
     */
    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    /**
     * @param  string  $categoryId
     */
    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return CategoryEntity|null
     */
    public function getCategory(): ?CategoryEntity
    {
        return $this->category;
    }

    /**
     * @param  CategoryEntity  $category
     */
    public function setCategory(CategoryEntity $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getSectionId(): ?string
    {
        return $this->sectionId;
    }

    /**
     * @param  string  $sectionId
     */
    public function setSectionId(string $sectionId): void
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @return CategoryEntity|null
     */
    public function getSection(): ?CategoryEntity
    {
        return $this->section;
    }

    /**
     * @param  CategoryEntity  $section
     */
    public function setSection(CategoryEntity $section): void
    {
        $this->section = $section;
    }

    public function readingTimeInMin()
    {
        $wordsPerMin = 225;

        $wordCount = str_word_count(strip_tags($this->translated['content'] ?? ''));

        $min = floor($wordCount / $wordsPerMin);

        return $min > 0 ? $min : 1;
    }

    public function getSalesChannels(): ?SalesChannelCollection
    {
        return $this->salesChannels;
    }

    public function setSalesChannels(SalesChannelCollection $salesChannels): void
    {
        $this->salesChannels = $salesChannels;
    }
}