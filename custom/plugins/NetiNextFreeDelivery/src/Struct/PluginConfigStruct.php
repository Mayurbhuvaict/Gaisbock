<?php

declare(strict_types=1);

namespace NetInventors\NetiNextFreeDelivery\Struct;

class PluginConfigStruct
{
    public const ARTICLE_DISPLAY_POSITION_ABOVE_PRICE        = 'abovePrice';

    public const ARTICLE_DISPLAY_POSITION_BEFORE_CART_BUTTON = 'beforeCartButton';

    public const ARTICLE_DISPLAY_POSITION_AFTER_CART_BUTTON  = 'afterCartButton';

    public const ARTICLE_DISPLAY_POSITION_LAST_POSITION      = 'lastPosition';

    private bool   $subshopActivated           = false;

    private float  $displayFrom                = 0.00;

    private bool   $showInArticle              = false;

    private string $articlePosition            = self::ARTICLE_DISPLAY_POSITION_LAST_POSITION;

    private bool   $showInHeader               = false;

    private bool   $showInModal                = false;

    private bool   $showProgressBar            = false;

    private bool   $hideDisplayForShippingFree = false;

    /**
     * @psalm-mutation-free
     */
    public function isSubshopActivated(): bool
    {
        return $this->subshopActivated;
    }

    /**
     * @psalm-mutation-free
     */
    public function getDisplayFrom(): float
    {
        return $this->displayFrom;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowInArticle(): bool
    {
        return $this->showInArticle;
    }

    /**
     * @psalm-mutation-free
     */
    public function getArticlePosition(): string
    {
        return $this->articlePosition;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowInHeader(): bool
    {
        return $this->showInHeader;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowInModal(): bool
    {
        return $this->showInModal;
    }

    /**
     * @psalm-mutation-free
     */
    public function isShowProgressBar(): bool
    {
        return $this->showProgressBar;
    }

    /**
     * @psalm-mutation-free
     */
    public function isHideDisplayForShippingFree(): bool
    {
        return $this->hideDisplayForShippingFree;
    }
}
