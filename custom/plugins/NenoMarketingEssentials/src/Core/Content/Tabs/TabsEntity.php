<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\Tabs;

use phpDocumentor\Reflection\Types\Integer;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class TabsEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $isGlobal;

    /**
     * @var string|null
     */
    protected $display;

    /**
     * @var int
     */
    protected $categoryId;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var string
     */
    protected $faviconId;

    // isGlobal
    public function getIsGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    public function setIsGlobal(int $isGlobal): void
    {
        $this->isGlobal = $isGlobal;
    }

    // display
    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): void
    {
        $this->display = $display;
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

    // faviconId
    public function getFaviconId(): string
    {
        return $this->faviconId;
    }

    public function setFaviconId(string $id): void
    {
        $this->faviconId = $id;
    }
}
