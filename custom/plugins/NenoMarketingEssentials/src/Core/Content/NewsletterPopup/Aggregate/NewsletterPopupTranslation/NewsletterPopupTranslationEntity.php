<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation;

use  Neno\MarketingEssentials\Core\Content\NewsletterPopup\NewsletterPopupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class NewsletterPopupTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $newsletterPopupId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var NewsletterPopupEntity
     */
    protected $newsletterPopup;

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
    protected $firstNameFieldPlaceholder;

    /**
     * @var string|null
     */
    protected $lastNameFieldPlaceholder;

    /**
     * @var string|null
     */
    protected $mailFieldPlaceholder;

    /**
     * @var string|null
     */
    protected $textSubscribeButton;

    /**
     * @var string|null
     */
    protected $textNonSubscribe;

    // newsletterPopupId
    public function getNewsletterPopupId(): string
    {
        return $this->newsletterPopupId;
    }

    public function setNewsletterPopupId(string $newsletterPopupId): void
    {
        $this->newsletterPopupId = $newsletterPopupId;
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

    // newsletterPopup

    public function getNewsletterPopup(): NewsletterPopupEntity
    {
        return $this->newsletterPopup;
    }

    public function setNewsletterPopup(NewsletterPopupEntity $newsletterPopup): void
    {
        $this->newsletterPopup = $newsletterPopup;
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

    // firstNameFieldPlaceholder

    public function getFirstNameFieldPlaceholder(): ?string
    {
        return $this->firstNameFieldPlaceholder;
    }

    public function setFirstNameFieldPlaceholder(string $firstNameFieldPlaceholder): void
    {
        $this->firstNameFieldPlaceholder = $firstNameFieldPlaceholder;
    }

    // lastNameFieldPlaceholder

    public function getLastNameFieldPlaceholder(): ?string
    {
        return $this->lastNameFieldPlaceholder;
    }

    public function setLastNameFieldPlaceholder(string $lastNameFieldPlaceholder): void
    {
        $this->lastNameFieldPlaceholder = $lastNameFieldPlaceholder;
    }

    // mailFieldPlaceholder

    public function getMailFieldPlaceholder(): ?string
    {
        return $this->mailFieldPlaceholder;
    }

    public function setMailFieldPlaceholder(string $mailFieldPlaceholder): void
    {
        $this->mailFieldPlaceholder = $mailFieldPlaceholder;
    }

    // textSubscribeButton

    public function getTextSubscribeButton(): ?string
    {
        return $this->textSubscribeButton;
    }

    public function setTextSubscribeButton(string $textSubscribeButton): void
    {
        $this->textSubscribeButton = $textSubscribeButton;
    }

    // textNonSubscribe

    public function getTextNonSubscribe(): ?string
    {
        return $this->textNonSubscribe;
    }

    public function setTextNonSubscribe(string $textNonSubscribe): void
    {
        $this->textNonSubscribe = $textNonSubscribe;
    }
}
