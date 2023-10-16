<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class GiftcardCodeService implements GiftcardCodeServiceInterface
{
    public function __construct(
        private SystemConfigService $systemConfigService
    ) {
    }

    public function generate(?string $salesChannelId = null): string
    {
        $length = (int)($this->systemConfigService->get('LaenenGiftcard.config.giftcardCodeLength', $salesChannelId) ?? 16);

        return $this->randomString($length);
    }

    private function randomString(int $length = 16): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVW23456789';

        return substr(str_shuffle(str_repeat($chars, (int)ceil($length / strlen($chars)))), 1, $length);
    }
}
