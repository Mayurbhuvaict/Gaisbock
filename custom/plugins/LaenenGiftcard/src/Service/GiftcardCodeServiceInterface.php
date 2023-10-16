<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Service;

interface GiftcardCodeServiceInterface
{
    public function generate(?string $salesChannelId = null): string;
}
