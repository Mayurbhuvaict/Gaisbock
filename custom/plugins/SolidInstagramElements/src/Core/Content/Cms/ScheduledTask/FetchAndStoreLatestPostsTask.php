<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class FetchAndStoreLatestPostsTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'solid_ie.fetch_and_store_latest_posts';
    }

    public static function getDefaultInterval(): int
    {
        return 300; // 5 minutes
    }
}
