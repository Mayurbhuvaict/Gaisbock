<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Aggregate\CmsSlotInstagramPost;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CmsSlotInstagramPostEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $userId;

    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $postId;

    /**
     * @var string|null
     */
    protected $caption;

    /**
     * @var string|null
     */
    protected $mediaType;

    /**
     * @var string|null
     */
    protected $mediaUrl;

    /**
     * @var string|null
     */
    protected $permalink;

    /**
     * @var string|null
     */
    protected $timestamp;

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPostId(): ?string
    {
        return $this->postId;
    }

    public function setPostId(string $postId): void
    {
        $this->postId = $postId;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(?string $mediaType): void
    {
        $this->mediaType = $mediaType;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function setMediaUrl(?string $mediaUrl): void
    {
        $this->mediaUrl = $mediaUrl;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setPermalink(?string $permalink): void
    {
        $this->permalink = $permalink;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(?string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}
