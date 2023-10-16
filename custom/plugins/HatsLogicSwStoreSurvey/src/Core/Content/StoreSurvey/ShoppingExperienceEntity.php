<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Core\Content\StoreSurvey;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ShoppingExperienceEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var CustomerEntity|null
     */
    protected $customer;

    /**
     * @var int
     */
    protected $points;

    /**
     * @var string
     */
    protected $comment;

    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
