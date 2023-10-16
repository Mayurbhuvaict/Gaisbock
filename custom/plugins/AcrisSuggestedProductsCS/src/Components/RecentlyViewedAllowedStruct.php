<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Components;

use Shopware\Core\Framework\Struct\Struct;

class RecentlyViewedAllowedStruct extends Struct
{
    public const EXTENSION_KEY = 'acris_recently_viewed_allowed';

    private bool $isAllowed;

    public function __construct(bool $isAllowed)
    {
        $this->isAllowed = $isAllowed;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }

    /**
     * @param bool $isAllowed
     */
    public function setIsAllowed(bool $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }
}
