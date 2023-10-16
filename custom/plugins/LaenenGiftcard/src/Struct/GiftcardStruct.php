<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Struct;

use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Shopware\Core\Framework\Struct\Struct;

class GiftcardStruct extends Struct
{
    protected ?string $name;

    protected ?string $description;

    protected ?float $balance;

    protected ?float $initialAmount;

    protected string $code;

    protected string $type;

    protected ?string $externalId;

    public static function createFromGiftcard(GiftcardEntity $giftcardEntity): self
    {
        /** @var GiftcardStruct $giftcard */
        $giftcard = self::createFrom($giftcardEntity);

        $giftcard->setType('local');
        $giftcard->setExternalId($giftcardEntity->getId());
        $giftcard->addExtension('entity', $giftcardEntity);

        return $giftcard;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): void
    {
        $this->balance = $balance;
    }

    public function getInitialAmount(): ?float
    {
        return $this->initialAmount;
    }

    public function setInitialAmount(?float $initialAmount): void
    {
        $this->initialAmount = $initialAmount;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }
}
