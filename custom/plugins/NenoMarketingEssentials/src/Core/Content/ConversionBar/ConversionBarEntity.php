<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar;

use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ConversionBarEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $salesChannelId;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var int|null
     */
    protected $sliderMaxWidth;

    /**
     * @var string|null
     */
    protected $backgroundColor;

    /**
     * @var string|null
     */
    protected $textColor;

    /**
     * @var string|null
     */
    protected $linkColor;

    /**
     * @var bool
     */
    protected $text01Clickable;

    /**
     * @var bool
     */
    protected $text01PrimaryActive;

    /**
     * @var string|null
     */
    protected $text01MediaId;

    /**
     * @var bool
     */
    protected $text02Clickable;

    /**
     * @var bool
     */
    protected $text02PrimaryActive;

    /**
     * @var string|null
     */
    protected $text02MediaId;

    /**
     * @var bool
     */
    protected $text03Clickable;

    /**
     * @var bool
     */
    protected $text03PrimaryActive;

    /**
     * @var string|null
     */
    protected $text03MediaId;

    /**
     * @var MediaEntity|null
     */
    protected $text01Media;

    /**
     * @var MediaEntity|null
     */
    protected $text02Media;

    /**
     * @var MediaEntity|null
     */
    protected $text03Media;

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(?string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    public function getSliderMaxWidth(): ?int
    {
        return $this->sliderMaxWidth;
    }

    public function setSliderMaxWidth(?int $sliderMaxWidth): void
    {
        $this->sliderMaxWidth = $sliderMaxWidth;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): void
    {
        $this->textColor = $textColor;
    }

    public function getLinkColor(): ?string
    {
        return $this->linkColor;
    }

    public function setLinkColor(?string $linkColor): void
    {
        $this->linkColor = $linkColor;
    }

    public function isText01Clickable(): bool
    {
        return $this->text01Clickable;
    }

    public function setText01Clickable(bool $text01Clickable): void
    {
        $this->text01Clickable = $text01Clickable;
    }

    public function isText01PrimaryActive(): bool
    {
        return $this->text01PrimaryActive;
    }

    public function setText01PrimaryActive(bool $text01PrimaryActive): void
    {
        $this->text01PrimaryActive = $text01PrimaryActive;
    }

    public function getText01MediaId(): ?string
    {
        return $this->text01MediaId;
    }

    public function setText01MediaId(?string $text01MediaId): void
    {
        $this->text01MediaId = $text01MediaId;
    }

    public function isText02Clickable(): bool
    {
        return $this->text02Clickable;
    }

    public function setText02Clickable(bool $text02Clickable): void
    {
        $this->text02Clickable = $text02Clickable;
    }

    public function isText02PrimaryActive(): bool
    {
        return $this->text02PrimaryActive;
    }

    public function setText02PrimaryActive(bool $text02PrimaryActive): void
    {
        $this->text02PrimaryActive = $text02PrimaryActive;
    }

    public function getText02MediaId(): ?string
    {
        return $this->text02MediaId;
    }

    public function setText02MediaId(?string $text02MediaId): void
    {
        $this->text02MediaId = $text02MediaId;
    }

    public function isText03Clickable(): bool
    {
        return $this->text03Clickable;
    }

    public function setText03Clickable(bool $text03Clickable): void
    {
        $this->text03Clickable = $text03Clickable;
    }

    public function isText03PrimaryActive(): bool
    {
        return $this->text03PrimaryActive;
    }

    public function setText03PrimaryActive(bool $text03PrimaryActive): void
    {
        $this->text03PrimaryActive = $text03PrimaryActive;
    }

    public function getText03MediaId(): ?string
    {
        return $this->text03MediaId;
    }

    public function setText03MediaId(?string $text03MediaId): void
    {
        $this->text03MediaId = $text03MediaId;
    }

    public function getText01Media(): ?MediaEntity
    {
        return $this->text01Media;
    }

    public function setText01Media(?MediaEntity $text01Media): void
    {
        $this->text01Media = $text01Media;
    }

    public function getText02Media(): ?MediaEntity
    {
        return $this->text02Media;
    }

    public function setText02Media(?MediaEntity $text02Media): void
    {
        $this->text02Media = $text02Media;
    }

    public function getText03Media(): ?MediaEntity
    {
        return $this->text03Media;
    }

    public function setText03Media(?MediaEntity $text03Media): void
    {
        $this->text03Media = $text03Media;
    }
}
