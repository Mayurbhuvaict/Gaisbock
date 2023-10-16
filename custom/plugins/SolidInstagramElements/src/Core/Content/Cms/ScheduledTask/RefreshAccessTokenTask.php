<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class RefreshAccessTokenTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'studio_solid.refresh_instagram_access_token';
    }

    public static function getDefaultInterval(): int
    {
        return 86400; // 24 hours
    }
}
