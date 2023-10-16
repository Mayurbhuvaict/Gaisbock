<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Components;

use Shopware\Core\Framework\Struct\Struct;

class TabOrderStruct extends Struct
{
    public const EXTENSION_KEY = 'acris_suggested_products_tab_order_struct';

    private array $tabs;

    public function __construct(array $tabs)
    {
        $this->tabs = $tabs;
    }

    /**
     * @return array
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * @param array $tabs
     */
    public function setTabs(array $tabs): void
    {
        $this->tabs = $tabs;
    }
}
