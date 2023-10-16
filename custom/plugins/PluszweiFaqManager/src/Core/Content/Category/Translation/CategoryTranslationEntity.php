<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category\Translation;


use Pluszwei\FaqManager\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use Shopware\Core\System\Language\LanguageEntity;

class CategoryTranslationEntity extends TranslationEntity
{
    use EntityCustomFieldsTrait;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $categoryId;

    /**
     * @var CategoryEntity|null
     */
    protected $category;

    /**
     * @var LanguageEntity|null
     */
    protected $language;

    /**
     * @var string|null
     */
    protected $breadcrumb;

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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param  string|null  $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getBreadcrumb(): ?string
    {
        return $this->breadcrumb;
    }

    /**
     * @param  string|null  $breadcrumb
     */
    public function setBreadcrumb(?string $breadcrumb): void
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param  string  $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
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
     * @param  CategoryEntity|null  $category
     */
    public function setCategory(?CategoryEntity $category): void
    {
        $this->category = $category;
    }

    /**
     * @return LanguageEntity|null
     */
    public function getLanguage(): ?LanguageEntity
    {
        return $this->language;
    }

    /**
     * @param  LanguageEntity|null  $language
     */
    public function setLanguage(?LanguageEntity $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param  string|null  $metaTitle
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
     * @param  string|null  $metaDescription
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
     * @param  string|null  $keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }
}