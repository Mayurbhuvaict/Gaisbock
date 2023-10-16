<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ConversionBar\Aggregate\ConversionBarTranslation;

use Neno\MarketingEssentials\Core\Content\ConversionBar\ConversionBarEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class ConversionBarTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $conversionBarId;

    /**
     * @var ConversionBarEntity
     */
    protected $conversionBar;

    /**
     * @var string|null
     */
    protected $text01;

    /**
     * @var string|null
     */
    protected $text01Url;

    /**
     * @var string|null
     */
    protected $text01Primary;

    /**
     * @var string|null
     */
    protected $text01PrimaryUrl;

    /**
     * @var string|null
     */
    protected $text02;

    /**
     * @var string|null
     */
    protected $text02Url;

    /**
     * @var string|null
     */
    protected $text02Primary;

    /**
     * @var string|null
     */
    protected $text02PrimaryUrl;

    /**
     * @var string|null
     */
    protected $text03;

    /**
     * @var string|null
     */
    protected $text03Url;

    /**
     * @var string|null
     */
    protected $text03Primary;

    /**
     * @var string|null
     */
    protected $text03PrimaryUrl;

    public function getConversionBarId(): string
    {
        return $this->conversionBarId;
    }

    public function setConversionBarId(string $conversionBarId): void
    {
        $this->conversionBarId = $conversionBarId;
    }

    public function getConversionBar(): ConversionBarEntity
    {
        return $this->conversionBar;
    }

    public function setConversionBar(ConversionBarEntity $conversionBar): void
    {
        $this->conversionBar = $conversionBar;
    }

    public function getText01(): ?string
    {
        return $this->text01;
    }

    public function setText01(?string $text01): void
    {
        $this->text01 = $text01;
    }

    public function getText01Url(): ?string
    {
        return $this->text01Url;
    }

    public function setText01Url(?string $text01Url): void
    {
        $this->text01Url = $text01Url;
    }

    public function getText01Primary(): ?string
    {
        return $this->text01Primary;
    }

    public function setText01Primary(?string $text01Primary): void
    {
        $this->text01Primary = $text01Primary;
    }

    public function getText01PrimaryUrl(): ?string
    {
        return $this->text01PrimaryUrl;
    }

    public function setText01PrimaryUrl(?string $text01PrimaryUrl): void
    {
        $this->text01PrimaryUrl = $text01PrimaryUrl;
    }

    public function getText02(): ?string
    {
        return $this->text02;
    }

    public function setText02(?string $text02): void
    {
        $this->text02 = $text02;
    }

    public function getText02Url(): ?string
    {
        return $this->text02Url;
    }

    public function setText02Url(?string $text02Url): void
    {
        $this->text02Url = $text02Url;
    }

    public function getText02Primary(): ?string
    {
        return $this->text02Primary;
    }

    public function setText02Primary(?string $text02Primary): void
    {
        $this->text02Primary = $text02Primary;
    }

    public function getText02PrimaryUrl(): ?string
    {
        return $this->text02PrimaryUrl;
    }

    public function setText02PrimaryUrl(?string $text02PrimaryUrl): void
    {
        $this->text02PrimaryUrl = $text02PrimaryUrl;
    }

    public function getText03(): ?string
    {
        return $this->text03;
    }

    public function setText03(?string $text03): void
    {
        $this->text03 = $text03;
    }

    public function getText03Url(): ?string
    {
        return $this->text03Url;
    }

    public function setText03Url(?string $text03Url): void
    {
        $this->text03Url = $text03Url;
    }

    public function getText03Primary(): ?string
    {
        return $this->text03Primary;
    }

    public function setText03Primary(?string $text03Primary): void
    {
        $this->text03Primary = $text03Primary;
    }

    public function getText03PrimaryUrl(): ?string
    {
        return $this->text03PrimaryUrl;
    }

    public function setText03PrimaryUrl(?string $text03PrimaryUrl): void
    {
        $this->text03PrimaryUrl = $text03PrimaryUrl;
    }
}
