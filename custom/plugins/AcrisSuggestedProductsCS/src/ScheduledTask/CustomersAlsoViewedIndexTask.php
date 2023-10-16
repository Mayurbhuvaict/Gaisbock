<?php declare(strict_types=1);

namespace Acris\SuggestedProducts\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class CustomersAlsoViewedIndexTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'acris_customers_also_viewed_index_task';
    }

    public static function getDefaultInterval(): int
    {
        return 86400; // 24 hours.
    }
}
