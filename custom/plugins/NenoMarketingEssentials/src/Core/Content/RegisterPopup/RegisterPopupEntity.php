<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\RegisterPopup;

use phpDocumentor\Reflection\Types\Integer;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class RegisterPopupEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $promotionActive;

    /**
     * @var bool
     */
    protected $promotionShowValidUntil;

    /**
     * @var string
     */
    protected $promotionId;

    /**
     * @var bool
     */
    protected $devMode;

    /**
     * @var string
     */
    protected $storageType;

    /**
     * @var bool
     */
    protected $isGlobal;

    /**
     * @var string|null
     */
    protected $visibleSettings;

    /**
     * @var int
     */
    protected $categoryId;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var string|null
     */
    protected $popupTrigger;

    /**
     * @var int
     */
    protected $popupTime;

    /**
     * @var int
     */
    protected $popupScroll;

    /**
     * @var int
     */
    protected $heightMobile;

    /**
     * @var int
     */
    protected $heightDesktop;

    /**
     * @var string|null
     */
    protected $headlineFontFamily;

    /**
     * @var int
     */
    protected $headlineFontSizeMobile;

    /**
     * @var int
     */
    protected $headlineLineHeightMobile;

    /**
     * @var int
     */
    protected $headlineFontSizeTablet;

    /**
     * @var int
     */
    protected $headlineLineHeightTablet;

    /**
     * @var int
     */
    protected $headlineFontSizeDesktop;

    /**
     * @var int
     */
    protected $headlineLineHeightDesktop;

    /**
     * @var string|null
     */
    protected $sublineFontFamily;

    /**
     * @var int
     */
    protected $sublineFontSizeMobile;

    /**
     * @var int
     */
    protected $sublineLineHeightMobile;

    /**
     * @var int
     */
    protected $sublineFontSizeTablet;

    /**
     * @var int
     */
    protected $sublineLineHeightTablet;

    /**
     * @var int
     */
    protected $sublineFontSizeDesktop;

    /**
     * @var int
     */
    protected $sublineLineHeightDesktop;

    /**
     * @var string|null
     */
    protected $promotionFontFamily;

    /**
     * @var int
     */
    protected $promotionFontSizeMobile;

    /**
     * @var int
     */
    protected $promotionLineHeightMobile;

    /**
     * @var int
     */
    protected $promotionFontSizeTablet;

    /**
     * @var int
     */
    protected $promotionLineHeightTablet;

    /**
     * @var int
     */
    protected $promotionFontSizeDesktop;

    /**
     * @var int
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
    protected $contentAlignment;

    /**
     * @var string
     */
    protected $registerMediaImageId;

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
    protected $submitButtonBackgroundColor;

    /**
     * @var string|null
     */
    protected $submitButtonBackgroundHoverColor;

    /**
     * @var string|null
     */
    protected $submitButtonTextColor;

    /**
     * @var string|null
     */
    protected $submitButtonTextHoverColor;

    /**
     * @var string|null
     */
    protected $nonSubmitTextColor;

    /**
     * @var string|null
     */
    protected $nonSubmitTextHoverColor;

    /**
     * @var int
     */
    protected $popupBorderRadius;

    /**
     * @var int
     */
    protected $submitButtonBorderRadius;

    // promotionActive
    public function getPromotionActive(): ?bool
    {
        return $this->promotionActive;
    }

    public function setPromotionActive(int $promotionActive): void
    {
        $this->promotionActive = $promotionActive;
    }

    // promotionShowValidUntil
    public function getPromotionShowValidUntil(): ?bool
    {
        return $this->promotionShowValidUntil;
    }

    public function setPromotionShowValidUntil(int $promotionShowValidUntil): void
    {
        $this->promotionShowValidUntil = $promotionShowValidUntil;
    }

    // promotionId
    public function getPromotionId(): string
    {
        return $this->promotionId;
    }

    public function setPromotionId(string $id): void
    {
        $this->promotionId = $id;
    }

    // devMode
    public function getDevMode(): ?bool
    {
        return $this->devMode;
    }

    public function setDevMode(int $devMode): void
    {
        $this->devMode = $devMode;
    }

    //storageType
    public function getStorageType(): ?string
    {
        return $this->storageType;
    }

    public function setStorageType(string $storageType): void
    {
        $this->storageType = $storageType;
    }

    // isGlobal
    public function getIsGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    public function setIsGlobal(int $isGlobal): void
    {
        $this->isGlobal = $isGlobal;
    }

    // visibleSettings
    public function getVisibleSettings(): ?string
    {
        return $this->visibleSettings;
    }

    public function setVisibleSettings(string $visibleSettings): void
    {
        $this->visibleSettings = $visibleSettings;
    }

    // categoryId

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    // productId

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    // popupTrigger
    public function getPopupTrigger(): ?string
    {
        return $this->popupTrigger;
    }

    public function setPopupTrigger(string $popupTrigger): void
    {
        $this->popupTrigger = $popupTrigger;
    }

    // popupTime
    public function getPopupTime(): ?int
    {
        return $this->popupTime;
    }

    public function setPopupTime(int $popupTime): void
    {
        $this->popupTime = $popupTime;
    }

    // popupScroll
    public function getPopupScroll(): ?int
    {
        return $this->popupScroll;
    }

    public function setPopupScroll(int $popupScroll): void
    {
        $this->popupScroll = $popupScroll;
    }

    // heightMobile
    public function getHeightMobile(): ?int
    {
        return $this->heightMobile;
    }

    public function setHeightMobile(int $heightMobile): void
    {
        $this->heightMobile = $heightMobile;
    }

    // heightDesktop
    public function getHeightDesktop(): ?int
    {
        return $this->heightDesktop;
    }

    public function setHeightDesktop(int $heightDesktop): void
    {
        $this->heightDesktop = $heightDesktop;
    }

    // headlineFontFamily
    public function getHeadlineFontFamily(): ?string
    {
        return $this->headlineFontFamily;
    }

    public function setHeadlineFontFamily(string $headlineFontFamily): void
    {
        $this->headlineFontFamily = $headlineFontFamily;
    }

    // headlineFontSizeMobile
    public function getHeadlineFontSizeMobile(): ?int
    {
        return $this->headlineFontSizeMobile;
    }

    public function setHeadlineFontSizeMobile(int $headlineFontSizeMobile): void
    {
        $this->headlineFontSizeMobile = $headlineFontSizeMobile;
    }

    // headlineLineHeightMobile
    public function getHeadlineLineHeightMobile(): ?int
    {
        return $this->headlineLineHeightMobile;
    }

    public function setHeadlineLineHeightMobile(int $headlineLineHeightMobile): void
    {
        $this->headlineLineHeightMobile = $headlineLineHeightMobile;
    }

    // headlineFontSizeTablet
    public function getHeadlineFontSizeTablet(): ?int
    {
        return $this->headlineFontSizeTablet;
    }

    public function setHeadlineFontSizeTablet(int $headlineFontSizeTablet): void
    {
        $this->headlineFontSizeTablet = $headlineFontSizeTablet;
    }

    // headlineLineHeightTablet
    public function getHeadlineLineHeightTablet(): ?int
    {
        return $this->headlineLineHeightTablet;
    }

    public function setHeadlineLineHeightTablet(int $headlineLineHeightTablet): void
    {
        $this->headlineLineHeightTablet = $headlineLineHeightTablet;
    }

    // headlineFontSizeDesktop
    public function getHeadlineFontSizeDesktop(): ?int
    {
        return $this->headlineFontSizeDesktop;
    }

    public function setHeadlineFontSizeDesktop(int $headlineFontSizeDesktop): void
    {
        $this->headlineFontSizeDesktop = $headlineFontSizeDesktop;
    }

    // headlineLineHeightDesktop
    public function getHeadlineLineHeightDesktop(): ?int
    {
        return $this->headlineLineHeightDesktop;
    }

    public function setHeadlineLineHeightDesktop(int $headlineLineHeightDesktop): void
    {
        $this->headlineLineHeightDesktop = $headlineLineHeightDesktop;
    }

    // sublineFontFamily
    public function getSublineFontFamily(): ?string
    {
        return $this->sublineFontFamily;
    }

    public function setSublineFontFamily(string $sublineFontFamily): void
    {
        $this->headlineFontFamily = $sublineFontFamily;
    }

    // sublineFontSizeMobile
    public function getSublineFontSizeMobile(): ?int
    {
        return $this->sublineFontSizeMobile;
    }

    public function setSublineFontSizeMobile(int $sublineFontSizeMobile): void
    {
        $this->sublineFontSizeMobile = $sublineFontSizeMobile;
    }

    // sublineLineHeightMobile
    public function getSublineLineHeightMobile(): ?int
    {
        return $this->sublineLineHeightMobile;
    }

    public function setSublineLineHeightMobile(int $sublineLineHeightMobile): void
    {
        $this->sublineLineHeightMobile = $sublineLineHeightMobile;
    }

    // sublineFontSizeTablet
    public function getSublineFontSizeTablet(): ?int
    {
        return $this->sublineFontSizeTablet;
    }

    public function setSublineFontSizeTablet(int $sublineFontSizeTablet): void
    {
        $this->sublineFontSizeTablet = $sublineFontSizeTablet;
    }

    // sublineLineHeightTablet
    public function getSublineLineHeightTablet(): ?int
    {
        return $this->sublineLineHeightTablet;
    }

    public function setSublineLineHeightTablet(int $sublineLineHeightTablet): void
    {
        $this->sublineLineHeightTablet = $sublineLineHeightTablet;
    }

    // sublineFontSizeDesktop
    public function getSublineFontSizeDesktop(): ?int
    {
        return $this->sublineFontSizeDesktop;
    }

    public function setSublineFontSizeDesktop(int $sublineFontSizeDesktop): void
    {
        $this->sublineFontSizeDesktop = $sublineFontSizeDesktop;
    }

    // sublineLineHeightDesktop
    public function getSublineLineHeightDesktop(): ?int
    {
        return $this->sublineLineHeightDesktop;
    }

    public function setSublineLineHeightDesktop(int $sublineLineHeightDesktop): void
    {
        $this->sublineLineHeightDesktop = $sublineLineHeightDesktop;
    }

    // promotionFontFamily
    public function getPromotionFontFamily(): ?string
    {
        return $this->promotionFontFamily;
    }

    public function setPromotionFontFamily(string $promotionFontFamily): void
    {
        $this->promotionFontFamily = $promotionFontFamily;
    }

    // promotionFontSizeMobile
    public function getPromotionFontSizeMobile(): ?int
    {
        return $this->promotionFontSizeMobile;
    }

    public function setPromotionFontSizeMobile(int $promotionFontSizeMobile): void
    {
        $this->promotionFontSizeMobile = $promotionFontSizeMobile;
    }

    // promotionLineHeightMobile
    public function getPromotionLineHeightMobile(): ?int
    {
        return $this->promotionLineHeightMobile;
    }

    public function setPromotionLineHeightMobile(int $promotionLineHeightMobile): void
    {
        $this->promotionLineHeightMobile = $promotionLineHeightMobile;
    }

    // promotionFontSizeTablet
    public function getPromotionFontSizeTablet(): ?int
    {
        return $this->promotionFontSizeTablet;
    }

    public function setPromotionFontSizeTablet(int $promotionFontSizeTablet): void
    {
        $this->promotionFontSizeTablet = $promotionFontSizeTablet;
    }

    // promotionLineHeightTablet
    public function getPromotionLineHeightTablet(): ?int
    {
        return $this->promotionLineHeightTablet;
    }

    public function setPromotionLineHeightTablet(int $promotionLineHeightTablet): void
    {
        $this->promotionLineHeightTablet = $promotionLineHeightTablet;
    }

    // promotionFontSizeDesktop
    public function getPromotionFontSizeDesktop(): ?int
    {
        return $this->promotionFontSizeDesktop;
    }

    public function setPromotionFontSizeDesktop(int $promotionFontSizeDesktop): void
    {
        $this->promotionFontSizeDesktop = $promotionFontSizeDesktop;
    }

    // promotionLineHeightDesktop
    public function getPromotionLineHeightDesktop(): ?int
    {
        return $this->promotionLineHeightDesktop;
    }

    public function setPromotionLineHeightDesktop(int $promotionLineHeightDesktop): void
    {
        $this->promotionLineHeightDesktop = $promotionLineHeightDesktop;
    }

    // mediaBackgroundColor
    public function getMediaBackgroundColor(): ?string
    {
        return $this->mediaBackgroundColor;
    }

    public function setMediaBackgroundColor(string $mediaBackgroundColor): void
    {
        $this->mediaBackgroundColor = $mediaBackgroundColor;
    }

    // imagePosition
    public function getImagePosition(): ?string
    {
        return $this->imagePosition;
    }

    public function setImagePosition(string $imagePosition): void
    {
        $this->imagePosition = $imagePosition;
    }

    // imageFit
    public function getImageFit(): ?string
    {
        return $this->imageFit;
    }

    public function setImageFit(string $imageFit): void
    {
        $this->imageFit = $imageFit;
    }

    // imageAlignment
    public function getImageAlignment(): ?string
    {
        return $this->imageAlignment;
    }

    public function setImageAlignment(string $imageAlignment): void
    {
        $this->imageAlignment = $imageAlignment;
    }

    // imageMobileSettings
    public function getImageMobileSettings(): ?string
    {
        return $this->imageMobileSettings;
    }

    public function setImageMobileSettings(string $imageMobileSettings): void
    {
        $this->imageMobileSettings = $imageMobileSettings;
    }

    // contentAlignment
    public function getContentAlignment(): ?string
    {
        return $this->contentAlignment;
    }

    public function setContentAlignment(string $contentAlignment): void
    {
        $this->contentAlignment = $contentAlignment;
    }

    // registerMediaImageId
    public function getRegisterMediaImageId(): string
    {
        return $this->registerMediaImageId;
    }

    public function setRegisterMediaImageId(string $id): void
    {
        $this->registerMediaImageId = $id;
    }

    // backgroundColor
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    // closeButtonColor
    public function getCloseButtonColor(): ?string
    {
        return $this->closeButtonColor;
    }

    public function setCloseButtonColor(string $closeButtonColor): void
    {
        $this->closeButtonColor = $closeButtonColor;
    }

    // closeButtonHoverColor
    public function getCloseButtonHoverColor(): ?string
    {
        return $this->closeButtonHoverColor;
    }

    public function setCloseButtonHoverColor(string $closeButtonHoverColor): void
    {
        $this->closeButtonHoverColor = $closeButtonHoverColor;
    }

    // promotionColor
    public function getPromotionColor(): ?string
    {
        return $this->promotionColor;
    }

    public function setPromotionColor(string $promotionColor): void
    {
        $this->promotionColor = $promotionColor;
    }

    // subscribeButtonBackgroundColor
    public function getSubmitButtonBackgroundColor(): ?string
    {
        return $this->submitButtonBackgroundColor;
    }

    public function setSubmitButtonBackgroundColor(string $submitButtonBackgroundColor): void
    {
        $this->submitButtonBackgroundColor = $submitButtonBackgroundColor;
    }

    // submitButtonBackgroundHoverColor
    public function getSubmitButtonBackgroundHoverColor(): ?string
    {
        return $this->submitButtonBackgroundHoverColor;
    }

    public function setSubmitButtonBackgroundHoverColor(string $submitButtonBackgroundHoverColor): void
    {
        $this->submitButtonBackgroundHoverColor = $submitButtonBackgroundHoverColor;
    }

    // submitButtonTextColor
    public function getSubmitButtonTextColor(): ?string
    {
        return $this->submitButtonTextColor;
    }

    public function setSubmitButtonTextColor(string $submitButtonTextColor): void
    {
        $this->submitButtonTextColor = $submitButtonTextColor;
    }

    // submitButtonTextHoverColor
    public function getSubmitButtonTextHoverColor(): ?string
    {
        return $this->submitButtonTextHoverColor;
    }

    public function setSubmitButtonTextHoverColor(string $submitButtonTextHoverColor): void
    {
        $this->submitButtonTextHoverColor = $submitButtonTextHoverColor;
    }

    // nonSubmitTextColor
    public function getNonSubmitTextColor(): ?string
    {
        return $this->nonSubmitTextColor;
    }

    public function setNonSubmitTextColor(string $nonSubmitTextColor): void
    {
        $this->nonSubmitTextColor = $nonSubmitTextColor;
    }

    // nonSubmitTextHoverColor
    public function getNonSubmitTextHoverColor(): ?string
    {
        return $this->nonSubmitTextHoverColor;
    }

    public function setNonSubmitTextHoverColor(string $nonSubmitTextHoverColor): void
    {
        $this->nonSubmitTextHoverColor = $nonSubmitTextHoverColor;
    }

    // popupBorderRadius
    public function getPopupBorderRadius(): ?int
    {
        return $this->popupBorderRadius;
    }

    public function setPopupBorderRadius(int $popupBorderRadius): void
    {
        $this->popupBorderRadius = $popupBorderRadius;
    }

    // submitButtonBorderRadius
    public function getSubmitButtonBorderRadius(): ?int
    {
        return $this->submitButtonBorderRadius;
    }

    public function setSubmitButtonBorderRadius(int $submitButtonBorderRadius): void
    {
        $this->submitButtonBorderRadius = $submitButtonBorderRadius;
    }
}
