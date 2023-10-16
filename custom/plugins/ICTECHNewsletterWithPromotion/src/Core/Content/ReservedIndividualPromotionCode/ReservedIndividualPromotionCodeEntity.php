<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Core\Content\ReservedIndividualPromotionCode;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ReservedIndividualPromotionCodeEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $promotionId;

    /**
     * @var string
     */
    protected $promotionIndividualCodeId;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getPromotionId(): string
    {
        return $this->promotionId;
    }

    public function setPromotionId(string $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    public function getPromotionIndividualCodeId(): string
    {
        return $this->promotionIndividualCodeId;
    }

    public function setPromotionIndividualCodeId(string $promotionIndividualCodeId): void
    {
        $this->promotionIndividualCodeId = $promotionIndividualCodeId;
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
}
