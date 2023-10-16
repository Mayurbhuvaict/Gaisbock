<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\ScheduledTask;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use StudioSolid\InstagramElements\Core\Content\Cms\Service\CmsSlotInstagramPostService;

/**
 * @internal
 */
final class FetchAndStoreLatestPostsTaskHandler extends ScheduledTaskHandler
{
    protected EntityRepository $scheduledTaskRepository;

    private CmsSlotInstagramPostService $cmsSlotInstagramPostService;

    public function __construct(
        EntityRepository $scheduledTaskRepository,
        CmsSlotInstagramPostService $cmsSlotInstagramPostService
    ) {
        parent::__construct($scheduledTaskRepository);
        $this->cmsSlotInstagramPostService = $cmsSlotInstagramPostService;
    }

    public static function getHandledMessages(): iterable
    {
        return [FetchAndStoreLatestPostsTask::class];
    }

    public function run(): void
    {
        $this->cmsSlotInstagramPostService->fetchAndStoreLatestPosts();
    }
}
