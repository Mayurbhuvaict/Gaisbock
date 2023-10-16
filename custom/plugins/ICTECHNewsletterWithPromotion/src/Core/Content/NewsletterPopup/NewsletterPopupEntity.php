<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\NewsletterPopup;

use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class NewsletterPopupEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool|null
     */
    protected $devMode;

    /**
     * @var string|null
     */
    protected $storageType;

    /**
     * @var bool|null
     */
    protected $isGlobal;

    /**
     * @var string|null
     */
    protected $visibleSettings;

    /**
     * @var string
     */
    protected $categoryId;

    /**
     * @var string|null
     */
    protected $productId;

    /**
     * @var string|null
     */
    protected $popupTrigger;

    /**
     * @var int|null
     */
    protected $popupTime;

    /**
     * @var int|null
     */
    protected $popupScroll;

    /**
     * @var int|null
     */
    protected $heightMobile;

    /**
     * @var int|null
     */
    protected $heightDesktop;

    /**
     * @var bool|null
     */
    protected $showFirstName;

    /**
     * @var bool|null
     */
    protected $showLastName;

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
    protected $textSubscribeButton;

    /**
     * @var string|null
     */
    protected $textNonSubscribe;

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
    protected $headlineFontFamily;

    /**
     * @var int|null
     */
    protected $headlineFontSizeMobile;

    /**
     * @var int|null
     */
    protected $headlineLineHeightMobile;

    /**
     * @var int|null
     */
    protected $headlineFontSizeTablet;

    /**
     * @var int|null
     */
    protected $headlineLineHeightTablet;

    /**
     * @var int|null
     */
    protected $headlineFontSizeDesktop;

    /**
     * @var int|null
     */
    protected $headlineLineHeightDesktop;

    /**
     * @var string|null
     */
    protected $sublineFontFamily;

    /**
     * @var int|null
     */
    protected $sublineFontSizeMobile;

    /**
     * @var int|null
     */
    protected $sublineLineHeightMobile;

    /**
     * @var int|null
     */
    protected $sublineFontSizeTablet;

    /**
     * @var int|null
     */
    protected $sublineLineHeightTablet;

    /**
     * @var int|null
     */
    protected $sublineFontSizeDesktop;

    /**
     * @var int|null
     */
    protected $sublineLineHeightDesktop;

    /**
     * @var string|null
     */
    protected $promotionFontFamily;

    /**
     * @var int|null
     */
    protected $promotionFontSizeMobile;

    /**
     * @var int|null
     */
    protected $promotionLineHeightMobile;

    /**
     * @var int|null
     */
    protected $promotionFontSizeTablet;

    /**
     * @var int|null
     */
    protected $promotionLineHeightTablet;

    /**
     * @var int|null
     */
    protected $promotionFontSizeDesktop;

    /**
     * @var int|null
     */
    protected $promotionLineHeightDesktop;

    /**
     * @var string|null
     */
    protected $mediaBackgroundColor;

    /**
     * @var string|null
     */
    protected $imagePosition;

    /**
     * @var string|null
     */
    protected $imageFit;

    /**
     * @var string|null
     */
    protected $imageAlignment;

    /**
     * @var string|null
     */
    protected $imageMobileSettings;

    /**
     * @var string|null
     */
    protected $backgroundColor;

    /**
     * @var string|null
     */
    protected $closeButtonColor;

    /**
     * @var string|null
     */
    protected $closeButtonHoverColor;

    /**
     * @var string|null
     */
    protected $promotionColor;

    /**
     * @var string|null
     */
    protected $mailFieldBorderColor;

    /**
     * @var string|null
     */
    protected $firstNameFieldBorderColor;

    /**
     * @var string|null
     */
    protected $lastNameFieldBorderColor;

    /**
     * @var string|null
     */
    protected $subscribeButtonBackgroundColor;

    /**
     * @var string|null
     */
    protected $subscribeButtonBackgroundHoverColor;

    /**
     * @var string|null
     */
    protected $subscribeButtonTextColor;

    /**
     * @var string|null
     */
    protected $subscribeButtonTextHoverColor;

    /**
     * @var string|null
     */
    protected $nonSubscribeTextColor;

    /**
     * @var string|null
     */
    protected $nonSubscribeTextHoverColor;

    /**
     * @var int|null
     */
    protected $popupBorderRadius;

    /**
     * @var int|null
     */
    protected $mailFieldBorderRadius;

    /**
     * @var int|null
     */
    protected $firstNameFieldBorderRadius;

    /**
     * @var int|null
     */
    protected $lastNameFieldBorderRadius;

    /**
     * @var int|null
     */
    protected $subscribeButtonBorderRadius;

    /**
     * @var string|null
     */
    protected $contentAlignment;

    /**
     * @var bool|null
     */
    protected $promotionActive;

    /**
     * @var bool|null
     */
    protected $promotionShowValidUntil;

    /**
     * @var string|null
     */
    protected $promotionId;

    /**
     * @var PromotionEntity|null
     */
    protected $promotion;

    /**
     * @var string|null
     */
    protected $mediaImageId;

    /**
     * @var MediaEntity|null
     */
    protected $mediaImage;

    /**
     * @var EntityCollection|null
     */
    protected $translations;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var array|null
     */
    protected $translated;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getDevMode(): ?bool
    {
        return $this->devMode;
    }

    public function setDevMode(?bool $devMode): void
    {
        $this->devMode = $devMode;
    }

    public function getStorageType(): ?string
    {
        return $this->storageType;
    }

    public function setStorageType(?string $storageType): void
    {
        $this->storageType = $storageType;
    }

    public function getIsGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    public function setIsGlobal(?bool $isGlobal): void
    {
        $this->isGlobal = $isGlobal;
    }

    public function getVisibleSettings(): ?string
    {
        return $this->visibleSettings;
    }

    public function setVisibleSettings(?string $visibleSettings): void
    {
        $this->visibleSettings = $visibleSettings;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
    }

    public function getPopupTrigger(): ?string
    {
        return $this->popupTrigger;
    }

    public function setPopupTrigger(?string $popupTrigger): void
    {
        $this->popupTrigger = $popupTrigger;
    }

    public function getPopupTime(): ?int
    {
        return $this->popupTime;
    }

    public function setPopupTime(?int $popupTime): void
    {
        $this->popupTime = $popupTime;
    }

    public function getPopupScroll(): ?int
    {
        return $this->popupScroll;
    }

    public function setPopupScroll(?int $popupScroll): void
    {
        $this->popupScroll = $popupScroll;
    }

    public function getHeightMobile(): ?int
    {
        return $this->heightMobile;
    }

    public function setHeightMobile(?int $heightMobile): void
    {
        $this->heightMobile = $heightMobile;
    }

    public function getHeightDesktop(): ?int
    {
        return $this->heightDesktop;
    }

    public function setHeightDesktop(?int $heightDesktop): void
    {
        $this->heightDesktop = $heightDesktop;
    }

    public function getShowFirstName(): ?bool
    {
        return $this->showFirstName;
    }

    public function setShowFirstName(?bool $showFirstName): void
    {
        $this->showFirstName = $showFirstName;
    }

    public function getShowLastName(): ?bool
    {
        return $this->showLastName;
    }

    public function setShowLastName(?bool $showLastName): void
    {
        $this->showLastName = $showLastName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
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

    public function getHeadlineFontFamily(): ?string
    {
        return $this->headlineFontFamily;
    }

    public function setHeadlineFontFamily(?string $headlineFontFamily): void
    {
        $this->headlineFontFamily = $headlineFontFamily;
    }

    public function getHeadlineFontSizeMobile(): ?int
    {
        return $this->headlineFontSizeMobile;
    }

    public function setHeadlineFontSizeMobile(?int $headlineFontSizeMobile): void
    {
        $this->headlineFontSizeMobile = $headlineFontSizeMobile;
    }

    public function getHeadlineLineHeightMobile(): ?int
    {
        return $this->headlineLineHeightMobile;
    }

    public function setHeadlineLineHeightMobile(?int $headlineLineHeightMobile): void
    {
        $this->headlineLineHeightMobile = $headlineLineHeightMobile;
    }

    public function getHeadlineFontSizeTablet(): ?int
    {
        return $this->headlineFontSizeTablet;
    }

    public function setHeadlineFontSizeTablet(?int $headlineFontSizeTablet): void
    {
        $this->headlineFontSizeTablet = $headlineFontSizeTablet;
    }

    public function getHeadlineLineHeightTablet(): ?int
    {
        return $this->headlineLineHeightTablet;
    }

    public function setHeadlineLineHeightTablet(?int $headlineLineHeightTablet): void
    {
        $this->headlineLineHeightTablet = $headlineLineHeightTablet;
    }

    public function getHeadlineFontSizeDesktop(): ?int
    {
        return $this->headlineFontSizeDesktop;
    }

    public function setHeadlineFontSizeDesktop(?int $headlineFontSizeDesktop): void
    {
        $this->headlineFontSizeDesktop = $headlineFontSizeDesktop;
    }

    public function getHeadlineLineHeightDesktop(): ?int
    {
        return $this->headlineLineHeightDesktop;
    }

    public function setHeadlineLineHeightDesktop(?int $headlineLineHeightDesktop): void
    {
        $this->headlineLineHeightDesktop = $headlineLineHeightDesktop;
    }

    public function getSublineFontFamily(): ?string
    {
        return $this->sublineFontFamily;
    }

    public function setSublineFontFamily(?string $sublineFontFamily): void
    {
        $this->sublineFontFamily = $sublineFontFamily;
    }

    public function getSublineFontSizeMobile(): ?int
    {
        return $this->sublineFontSizeMobile;
    }

    public function setSublineFontSizeMobile(?int $sublineFontSizeMobile): void
    {
        $this->sublineFontSizeMobile = $sublineFontSizeMobile;
    }

    public function getSublineLineHeightMobile(): ?int
    {
        return $this->sublineLineHeightMobile;
    }

    public function setSublineLineHeightMobile(?int $sublineLineHeightMobile): void
    {
        $this->sublineLineHeightMobile = $sublineLineHeightMobile;
    }

    public function getSublineFontSizeTablet(): ?int
    {
        return $this->sublineFontSizeTablet;
    }

    public function setSublineFontSizeTablet(?int $sublineFontSizeTablet): void
    {
        $this->sublineFontSizeTablet = $sublineFontSizeTablet;
    }

    public function getSublineLineHeightTablet(): ?int
    {
        return $this->sublineLineHeightTablet;
    }

    public function setSublineLineHeightTablet(?int $sublineLineHeightTablet): void
    {
        $this->sublineLineHeightTablet = $sublineLineHeightTablet;
    }

    public function getSublineFontSizeDesktop(): ?int
    {
        return $this->sublineFontSizeDesktop;
    }

    public function setSublineFontSizeDesktop(?int $sublineFontSizeDesktop): void
    {
        $this->sublineFontSizeDesktop = $sublineFontSizeDesktop;
    }

    public function getSublineLineHeightDesktop(): ?int
    {
        return $this->sublineLineHeightDesktop;
    }

    public function setSublineLineHeightDesktop(?int $sublineLineHeightDesktop): void
    {
        $this->sublineLineHeightDesktop = $sublineLineHeightDesktop;
    }

    public function getPromotionFontFamily(): ?string
    {
        return $this->promotionFontFamily;
    }

    public function setPromotionFontFamily(?string $promotionFontFamily): void
    {
        $this->promotionFontFamily = $promotionFontFamily;
    }

    public function getPromotionFontSizeMobile(): ?int
    {
        return $this->promotionFontSizeMobile;
    }

    public function setPromotionFontSizeMobile(?int $promotionFontSizeMobile): void
    {
        $this->promotionFontSizeMobile = $promotionFontSizeMobile;
    }

    public function getPromotionLineHeightMobile(): ?int
    {
        return $this->promotionLineHeightMobile;
    }

    public function setPromotionLineHeightMobile(?int $promotionLineHeightMobile): void
    {
        $this->promotionLineHeightMobile = $promotionLineHeightMobile;
    }

    public function getPromotionFontSizeTablet(): ?int
    {
        return $this->promotionFontSizeTablet;
    }

    public function setPromotionFontSizeTablet(?int $promotionFontSizeTablet): void
    {
        $this->promotionFontSizeTablet = $promotionFontSizeTablet;
    }

    public function getPromotionLineHeightTablet(): ?int
    {
        return $this->promotionLineHeightTablet;
    }

    public function setPromotionLineHeightTablet(?int $promotionLineHeightTablet): void
    {
        $this->promotionLineHeightTablet = $promotionLineHeightTablet;
    }

    public function getPromotionFontSizeDesktop(): ?int
    {
        return $this->promotionFontSizeDesktop;
    }

    public function setPromotionFontSizeDesktop(?int $promotionFontSizeDesktop): void
    {
        $this->promotionFontSizeDesktop = $promotionFontSizeDesktop;
    }

    public function getPromotionLineHeightDesktop(): ?int
    {
        return $this->promotionLineHeightDesktop;
    }

    public function setPromotionLineHeightDesktop(?int $promotionLineHeightDesktop): void
    {
        $this->promotionLineHeightDesktop = $promotionLineHeightDesktop;
    }

    public function getMediaBackgroundColor(): ?string
    {
        return $this->mediaBackgroundColor;
    }

    public function setMediaBackgroundColor(?string $mediaBackgroundColor): void
    {
        $this->mediaBackgroundColor = $mediaBackgroundColor;
    }

    public function getImagePosition(): ?string
    {
        return $this->imagePosition;
    }

    public function setImagePosition(?string $imagePosition): void
    {
        $this->imagePosition = $imagePosition;
    }

    public function getImageFit(): ?string
    {
        return $this->imageFit;
    }

    public function setImageFit(?string $imageFit): void
    {
        $this->imageFit = $imageFit;
    }

    public function getImageAlignment(): ?string
    {
        return $this->imageAlignment;
    }

    public function setImageAlignment(?string $imageAlignment): void
    {
        $this->imageAlignment = $imageAlignment;
    }

    public function getImageMobileSettings(): ?string
    {
        return $this->imageMobileSettings;
    }

    public function setImageMobileSettings(?string $imageMobileSettings): void
    {
        $this->imageMobileSettings = $imageMobileSettings;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getCloseButtonColor(): ?string
    {
        return $this->closeButtonColor;
    }

    public function setCloseButtonColor(?string $closeButtonColor): void
    {
        $this->closeButtonColor = $closeButtonColor;
    }

    public function getCloseButtonHoverColor(): ?string
    {
        return $this->closeButtonHoverColor;
    }

    public function setCloseButtonHoverColor(?string $closeButtonHoverColor): void
    {
        $this->closeButtonHoverColor = $closeButtonHoverColor;
    }

    public function getPromotionColor(): ?string
    {
        return $this->promotionColor;
    }

    public function setPromotionColor(?string $promotionColor): void
    {
        $this->promotionColor = $promotionColor;
    }

    public function getMailFieldBorderColor(): ?string
    {
        return $this->mailFieldBorderColor;
    }

    public function setMailFieldBorderColor(?string $mailFieldBorderColor): void
    {
        $this->mailFieldBorderColor = $mailFieldBorderColor;
    }

    public function getFirstNameFieldBorderColor(): ?string
    {
        return $this->firstNameFieldBorderColor;
    }

    public function setFirstNameFieldBorderColor(?string $firstNameFieldBorderColor): void
    {
        $this->firstNameFieldBorderColor = $firstNameFieldBorderColor;
    }

    public function getLastNameFieldBorderColor(): ?string
    {
        return $this->lastNameFieldBorderColor;
    }

    public function setLastNameFieldBorderColor(?string $lastNameFieldBorderColor): void
    {
        $this->lastNameFieldBorderColor = $lastNameFieldBorderColor;
    }

    public function getSubscribeButtonBackgroundColor(): ?string
    {
        return $this->subscribeButtonBackgroundColor;
    }

    public function setSubscribeButtonBackgroundColor(?string $subscribeButtonBackgroundColor): void
    {
        $this->subscribeButtonBackgroundColor = $subscribeButtonBackgroundColor;
    }

    public function getSubscribeButtonBackgroundHoverColor(): ?string
    {
        return $this->subscribeButtonBackgroundHoverColor;
    }

    public function setSubscribeButtonBackgroundHoverColor(?string $subscribeButtonBackgroundHoverColor): void
    {
        $this->subscribeButtonBackgroundHoverColor = $subscribeButtonBackgroundHoverColor;
    }

    public function getSubscribeButtonTextColor(): ?string
    {
        return $this->subscribeButtonTextColor;
    }

    public function setSubscribeButtonTextColor(?string $subscribeButtonTextColor): void
    {
        $this->subscribeButtonTextColor = $subscribeButtonTextColor;
    }

    public function getSubscribeButtonTextHoverColor(): ?string
    {
        return $this->subscribeButtonTextHoverColor;
    }

    public function setSubscribeButtonTextHoverColor(?string $subscribeButtonTextHoverColor): void
    {
        $this->subscribeButtonTextHoverColor = $subscribeButtonTextHoverColor;
    }

    public function getNonSubscribeTextColor(): ?string
    {
        return $this->nonSubscribeTextColor;
    }

    public function setNonSubscribeTextColor(?string $nonSubscribeTextColor): void
    {
        $this->nonSubscribeTextColor = $nonSubscribeTextColor;
    }

    public function getNonSubscribeTextHoverColor(): ?string
    {
        return $this->nonSubscribeTextHoverColor;
    }

    public function setNonSubscribeTextHoverColor(?string $nonSubscribeTextHoverColor): void
    {
        $this->nonSubscribeTextHoverColor = $nonSubscribeTextHoverColor;
    }

    public function getPopupBorderRadius(): ?int
    {
        return $this->popupBorderRadius;
    }

    public function setPopupBorderRadius(?int $popupBorderRadius): void
    {
        $this->popupBorderRadius = $popupBorderRadius;
    }

    public function getMailFieldBorderRadius(): ?int
    {
        return $this->mailFieldBorderRadius;
    }

    public function setMailFieldBorderRadius(?int $mailFieldBorderRadius): void
    {
        $this->mailFieldBorderRadius = $mailFieldBorderRadius;
    }

    public function getFirstNameFieldBorderRadius(): ?int
    {
        return $this->firstNameFieldBorderRadius;
    }

    public function setFirstNameFieldBorderRadius(?int $firstNameFieldBorderRadius): void
    {
        $this->firstNameFieldBorderRadius = $firstNameFieldBorderRadius;
    }

    public function getLastNameFieldBorderRadius(): ?int
    {
        return $this->lastNameFieldBorderRadius;
    }

    public function setLastNameFieldBorderRadius(?int $lastNameFieldBorderRadius): void
    {
        $this->lastNameFieldBorderRadius = $lastNameFieldBorderRadius;
    }

    public function getSubscribeButtonBorderRadius(): ?int
    {
        return $this->subscribeButtonBorderRadius;
    }

    public function setSubscribeButtonBorderRadius(?int $subscribeButtonBorderRadius): void
    {
        $this->subscribeButtonBorderRadius = $subscribeButtonBorderRadius;
    }

    public function getContentAlignment(): ?string
    {
        return $this->contentAlignment;
    }

    public function setContentAlignment(?string $contentAlignment): void
    {
        $this->contentAlignment = $contentAlignment;
    }

    public function getPromotionActive(): ?bool
    {
        return $this->promotionActive;
    }

    public function setPromotionActive(?bool $promotionActive): void
    {
        $this->promotionActive = $promotionActive;
    }

    public function getPromotionShowValidUntil(): ?bool
    {
        return $this->promotionShowValidUntil;
    }

    public function setPromotionShowValidUntil(?bool $promotionShowValidUntil): void
    {
        $this->promotionShowValidUntil = $promotionShowValidUntil;
    }

    public function getPromotionId(): ?string
    {
        return $this->promotionId;
    }

    public function setPromotionId(?string $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    public function getPromotion(): ?PromotionEntity
    {
        return $this->promotion;
    }

    public function setPromotion(?PromotionEntity $promotion): void
    {
        $this->promotion = $promotion;
    }

    public function getMediaImageId(): ?string
    {
        return $this->mediaImageId;
    }

    public function setMediaImageId(?string $mediaImageId): void
    {
        $this->mediaImageId = $mediaImageId;
    }

    public function getMediaImage(): ?MediaEntity
    {
        return $this->mediaImage;
    }

    public function setMediaImage(?MediaEntity $mediaImage): void
    {
        $this->mediaImage = $mediaImage;
    }

    public function getTranslations(): EntityCollection
    {
        return $this->translations;
    }

    public function setTranslations(EntityCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
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

    public function getTranslated(): array
    {
        return $this->translated;
    }

    public function setTranslated(array $translated): void
    {
        $this->translated = $translated;
    }
}
