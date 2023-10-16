<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup\Aggregate\NewsletterPopupTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\Language\LanguageEntity;

class NewsletterPopupTranslationEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

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

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $newsletterPopupId;

    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var ArrayEntity|null
     */
    protected $newsletterPopup;

    /**
     * @var LanguageEntity|null
     */
    protected $language;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): void
    {
        $this->headline = $headline;
    }

    public function getSubline(): ?string
    {
        return $this->subline;
    }

    public function setSubline(?string $subline): void
    {
        $this->subline = $subline;
    }

    public function getPromotionTextValidUntil(): ?string
    {
        return $this->promotionTextValidUntil;
    }

    public function setPromotionTextValidUntil(?string $promotionTextValidUntil): void
    {
        $this->promotionTextValidUntil = $promotionTextValidUntil;
    }

    public function getFirstNameFieldPlaceholder(): ?string
    {
        return $this->firstNameFieldPlaceholder;
    }

    public function setFirstNameFieldPlaceholder(?string $firstNameFieldPlaceholder): void
    {
        $this->firstNameFieldPlaceholder = $firstNameFieldPlaceholder;
    }

    public function getLastNameFieldPlaceholder(): ?string
    {
        return $this->lastNameFieldPlaceholder;
    }

    public function setLastNameFieldPlaceholder(?string $lastNameFieldPlaceholder): void
    {
        $this->lastNameFieldPlaceholder = $lastNameFieldPlaceholder;
    }

    public function getMailFieldPlaceholder(): ?string
    {
        return $this->mailFieldPlaceholder;
    }

    public function setMailFieldPlaceholder(?string $mailFieldPlaceholder): void
    {
        $this->mailFieldPlaceholder = $mailFieldPlaceholder;
    }

    public function getTextSubscribeButton(): ?string
    {
        return $this->textSubscribeButton;
    }

    public function setTextSubscribeButton(?string $textSubscribeButton): void
    {
        $this->textSubscribeButton = $textSubscribeButton;
    }

    public function getTextNonSubscribe(): ?string
    {
        return $this->textNonSubscribe;
    }

    public function setTextNonSubscribe(?string $textNonSubscribe): void
    {
        $this->textNonSubscribe = $textNonSubscribe;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getNewsletterPopupId(): string
    {
        return $this->newsletterPopupId;
    }

    public function setNewsletterPopupId(string $newsletterPopupId): void
    {
        $this->newsletterPopupId = $newsletterPopupId;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): void
    {
        $this->languageId = $languageId;
    }

    public function getNewsletterPopup(): ?ArrayEntity
    {
        return $this->newsletterPopup;
    }

    public function setNewsletterPopup(?ArrayEntity $newsletterPopup): void
    {
        $this->newsletterPopup = $newsletterPopup;
    }

    public function getLanguage(): ?LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEntity $language): void
    {
        $this->language = $language;
    }
}