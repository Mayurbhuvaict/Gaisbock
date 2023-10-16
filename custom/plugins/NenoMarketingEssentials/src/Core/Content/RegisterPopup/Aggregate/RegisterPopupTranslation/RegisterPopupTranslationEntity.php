<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup\Aggregate\RegisterPopupTranslation;

use  Neno\MarketingEssentials\Core\Content\RegisterPopup\RegisterPopupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class RegisterPopupTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $registerPopupId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var RegisterPopupEntity
     */
    protected $registerPopup;

    /**
     * @var string|null
     */
    protected $headline;

    /**
     * @var string|null
     */
    protected $subline;

    /**
     * @var string|null
     */
    protected $promotionTextValidUntil;

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var string|null
     */
    protected $textSubmitButton;

    /**
     * @var string|null
     */
    protected $textNonSubmit;

    // registerPopupId
    public function getRegisterPopupId(): string
    {
        return $this->registerPopupId;
    }

    public function setRegisterPopupId(string $registerPopupId): void
    {
        $this->registerPopupId = $registerPopupId;
    }

    // name

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // registerPopup

    public function getRegisterPopup(): RegisterPopupEntity
    {
        return $this->registerPopup;
    }

    public function setRegisterPopup(RegisterPopupEntity $registerPopup): void
    {
        $this->registerPopup = $registerPopup;
    }

    // headline

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    // subline

    public function getSubline(): ?string
    {
        return $this->subline;
    }

    public function setSubline(string $subline): void
    {
        $this->subline = $subline;
    }

    // promotionTextValidUntil

    public function getPromotionTextValidUntil(): ?string
    {
        return $this->promotionTextValidUntil;
    }

    public function setPromotionTextValidUntil(string $promotionTextValidUntil): void
    {
        $this->promotionTextValidUntil = $promotionTextValidUntil;
    }

    // text

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }



    // textSubmitButton

    public function getTextSubmitButton(): ?string
    {
        return $this->textSubmitButton;
    }

    public function setTextSubmitButton(string $textSubmitButton): void
    {
        $this->textSubmitButton = $textSubmitButton;
    }

    // textNonSubmit

    public function getTextNonSubmit(): ?string
    {
        return $this->textNonSubmit;
    }

    public function setTextNonSubmit(string $textNonSubmit): void
    {
        $this->textNonSubmit = $textNonSubmit;
    }
}
