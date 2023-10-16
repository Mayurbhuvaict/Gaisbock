<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article\Translation;


use Pluszwei\FaqManager\Core\Content\Article\ArticleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ArticleTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $articleId;

    /**
     * @var ArticleEntity
     */
    protected $article;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $teaser;

    /**
     * @var string|null
     */
    protected $content;

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
     * @return string
     */
    public function getArticleId(): string
    {
        return $this->articleId;
    }

    /**
     * @param  string  $articleId
     */
    public function setArticleId(string $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return ArticleEntity
     */
    public function getArticle(): ArticleEntity
    {
        return $this->article;
    }

    /**
     * @param  ArticleEntity  $article
     */
    public function setArticle(ArticleEntity $article): void
    {
        $this->article = $article;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param  string  $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * @param  string  $teaser
     */
    public function setTeaser(string $teaser): void
    {
        $this->teaser = $teaser;
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