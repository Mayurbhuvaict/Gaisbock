<?php declare(strict_types=1);

namespace Swag\Security\Tests\State;

use Swag\Security\Components\AbstractSecurityFix;

/**
 * @internal
 */
class TestFix extends AbstractSecurityFix
{
    public static function getTicket(): string
    {
        return 'test';
    }

    public static function getMinVersion(): string
    {
        return '6.5.0';
    }

    public static function getMaxVersion(): ?string
    {
        return '6.5.1';
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
