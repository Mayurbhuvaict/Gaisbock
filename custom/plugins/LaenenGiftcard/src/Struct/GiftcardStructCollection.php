<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Struct;

use Shopware\Core\Framework\Struct\Collection;

class GiftcardStructCollection extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return GiftcardStruct::class;
    }

    public function add($element): void
    {
        $this->validateType($element);

        if (!$element instanceof GiftcardStruct) {
            return;
        }

        $this->elements[$element->getCode()] = $element;
    }
}
