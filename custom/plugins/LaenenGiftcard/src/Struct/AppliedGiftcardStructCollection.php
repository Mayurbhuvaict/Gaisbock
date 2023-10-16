<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Struct;

use Shopware\Core\Framework\Struct\Collection;

/**
 * @extends Collection<AppliedGiftcardStruct>
 */
class AppliedGiftcardStructCollection extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return AppliedGiftcardStruct::class;
    }

    public function getCodes(): array
    {
        return array_map(function (AppliedGiftcardStruct $giftcard) {
            return $giftcard->getCode();
        }, $this->elements);
    }

    public function getAppliedTotal(): float
    {
        return array_sum(array_map(function (AppliedGiftcardStruct $giftcard) {
            return $giftcard->getAppliedAmount();
        }, $this->elements));
    }

    public function add($element): void
    {
        $this->validateType($element);

        if (!$element instanceof AppliedGiftcardStruct) {
            return;
        }

        $this->elements[$element->getCode()] = $element;
    }
}
