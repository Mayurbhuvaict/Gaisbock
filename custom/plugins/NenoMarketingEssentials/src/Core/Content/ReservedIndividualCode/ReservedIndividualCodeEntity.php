<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Core\Content\ReservedIndividualCode;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ReservedIndividualCodeEntity extends Entity {
    use EntityIdTrait;

    protected string $promotionIndividualCodeId;

    protected string $promotionId;

    public function getPromotionIndividualCodeId(): string
    {
        return $this->promotionIndividualCodeId;
    }

    public function setPromotionIndividualCodeId(string $promotionIndividualCodeId): void
    {
        $this->promotionIndividualCodeId = $promotionIndividualCodeId;
    }

    public function getPromotionId(): string
    {
        return $this->promotionId;
    }

    public function setPromotionId(string $promotionId): void
    {
        $this->promotionId = $promotionId;
    }
}
