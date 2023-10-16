<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\Custom;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CustomersAlsoViewedEntity extends Entity
{
    use EntityIdTrait;

    /**
     * string
     */
    protected $productId;

    /**
     * string
     */
    protected $viewedProductId;

    /**
     * string
     */
    protected $sessionId;

    /**
     * string
     */
    protected $salesChannelId;

    /**
     * string
     */
    protected $rowHash;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getViewedProductId()
    {
        return $this->viewedProductId;
    }

    /**
     * @param mixed $viewedProductId
     */
    public function setViewedProductId($viewedProductId): void
    {
        $this->viewedProductId = $viewedProductId;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     */
    public function setSessionId($sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @return mixed
     */
    public function getSalesChannelId()
    {
        return $this->salesChannelId;
    }

    /**
     * @param mixed $salesChannelId
     */
    public function setSalesChannelId($salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    /**
     * @return mixed
     */
    public function getRowHash()
    {
        return $this->rowHash;
    }

    /**
     * @param mixed $rowHash
     */
    public function setRowHash($rowHash): void
    {
        $this->rowHash = $rowHash;
    }
}
