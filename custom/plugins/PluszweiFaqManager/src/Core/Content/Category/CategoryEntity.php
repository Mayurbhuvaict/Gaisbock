<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Pluszwei\FaqManager\Core\Content\Article\ArticleCollection;
use Pluszwei\FaqManager\Core\Content\Category\Translation\CategoryTranslationCollection;
use Shopware\Core\Content\Seo\SeoUrl\SeoUrlCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelCollection;

class CategoryEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $parentId;

    /**
     * @var string|null
     */
    protected $afterCategoryId;

    /**
     * @var string|null
     */
    protected $navigationId;

    /**
     * @var \Shopware\Core\Content\Category\CategoryEntity|null
     */
    protected $navigation;

    /**
     * @var int|null
     */
    protected $level;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var int|null
     */
    protected $childCount;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|null
     */
    protected $breadcrumb;

    /**
     * @var self|null
     */
    protected $parent;

    /**
     * @var CategoryCollection|null
     */
    protected $children;

    /**
     * @var CategoryTranslationCollection|null
     */
    protected $translations;

    /**
     * @var ArticleCollection|null
     */
    protected $categoryArticles;

    /**
     * @var ArticleCollection|null
     */
    protected $sectionArticles;

    /**
     * @var SeoUrlCollection|null
     */
    protected $seoUrls;

    /**
     * @var SalesChannelCollection|null
     */
    protected $salesChannels;

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param  string|null  $parentId
     */
    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string|null
     */
    public function getAfterCategoryId(): ?string
    {
        return $this->afterCategoryId;
    }

    /**
     * @param  string|null  $afterCategoryId
     */
    public function setAfterCategoryId(?string $afterCategoryId): void
    {
        $this->afterCategoryId = $afterCategoryId;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param  int|null  $level
     */
    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param  string|null  $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return int|null
     */
    public function getChildCount(): ?int
    {
        return $this->childCount;
    }

    /**
     * @param  int|null  $childCount
     */
    public function setChildCount(?int $childCount): void
    {
        $this->childCount = $childCount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return CategoryEntity|null
     */
    public function getParent(): ?CategoryEntity
    {
        return $this->parent;
    }

    /**
     * @param  CategoryEntity|null  $parent
     */
    public function setParent(?CategoryEntity $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string|null
     */
    public function getNavigationId(): ?string
    {
        return $this->navigationId;
    }

    /**
     * @param  string|null  $navigationId
     */
    public function setNavigationId(?string $navigationId): void
    {
        $this->navigationId = $navigationId;
    }

    /**
     * @return \Shopware\Core\Content\Category\CategoryEntity|null
     */
    public function getNavigation(): ?\Shopware\Core\Content\Category\CategoryEntity
    {
        return $this->navigation;
    }

    /**
     * @param  \Shopware\Core\Content\Category\CategoryEntity|null  $navigation
     */
    public function setNavigation(?\Shopware\Core\Content\Category\CategoryEntity $navigation): void
    {
        $this->navigation = $navigation;
    }

    /**
     * @return CategoryCollection|null
     */
    public function getChildren(): ?CategoryCollection
    {
        return $this->children;
    }

    /**
     * @param  CategoryCollection|null  $children
     */
    public function setChildren(?CategoryCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return CategoryTranslationCollection|null
     */
    public function getTranslations(): ?CategoryTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param  CategoryTranslationCollection|null  $translations
     */
    public function setTranslations(?CategoryTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * @return ArticleCollection|null
     */
    public function getCategoryArticles(): ?ArticleCollection
    {
        return $this->categoryArticles;
    }

    /**
     * @param  ArticleCollection|null  $categoryArticles
     */
    public function setCategoryArticles(?ArticleCollection $categoryArticles): void
    {
        $this->categoryArticles = $categoryArticles;
    }

    /**
     * @return ArticleCollection|null
     */
    public function getSectionArticles(): ?ArticleCollection
    {
        return $this->sectionArticles;
    }

    /**
     * @param  ArticleCollection|null  $sectionArticles
     */
    public function setSectionArticles(?ArticleCollection $sectionArticles): void
    {
        $this->sectionArticles = $sectionArticles;
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

    public function getBreadcrumb(): array
    {
        return array_values($this->getPlainBreadcrumb());
    }

    public function getPlainBreadcrumb(): array
    {
        $breadcrumb = $this->getTranslation('breadcrumb');
        if ($breadcrumb === null) {
            return [];
        }
        if ($this->path === null) {
            return $breadcrumb;
        }

        $parts = \array_slice(explode('|', $this->path), 1, -1);

        $filtered = [];
        foreach ($parts as $id) {
            if (isset($breadcrumb[$id])) {
                $filtered[$id] = $breadcrumb[$id];
            }
        }

        $filtered[$this->getId()] = $breadcrumb[$this->getId()];

        return $filtered;
    }

    public function setBreadcrumb(?array $breadcrumb): void
    {
        $this->breadcrumb = $breadcrumb;
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data['translated']['breadcrumb'] = $data['breadcrumb'] = $this->getBreadcrumb();

        return $data;
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