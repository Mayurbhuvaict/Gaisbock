<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use StudioSolid\InstagramElements\Core\Content\Cms\Aggregate\CmsSlotInstagramPost\CmsSlotInstagramPostCollection;

class FeedCmsElementResolver extends AbstractCmsElementResolver
{
    private SystemConfigService $systemConfigService;

    private EntityRepository $cmsSlotInstagramPostRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepository $cmsSlotInstagramPostRepository
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->cmsSlotInstagramPostRepository = $cmsSlotInstagramPostRepository;
    }

    public function getType(): string
    {
        return 'solid-ie-feed';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        return null;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $salesChannelContext = $resolverContext->getSalesChannelContext();
        $salesChannelId = $salesChannelContext->getSalesChannelId();
        $salesChannelUseDatabaseAndFilesystem = $this->getSalesChannelUseDatabaseAndFilesystem($salesChannelId);

        if (!$salesChannelUseDatabaseAndFilesystem) {
            return;
        }

        $salesChannelAccessToken = $this->getSalesChannelAccessToken($salesChannelId);

        if (!$salesChannelAccessToken || !\array_key_exists('userId', $salesChannelAccessToken)) {
            return;
        }

        $userId = $salesChannelAccessToken['userId'];
        $userPosts = $this->getUserPosts($userId, $salesChannelContext->getContext());

        if ($userPosts) {
            $indexedUserPosts = [];

            foreach ($userPosts as $post) {
                array_push($indexedUserPosts, $post);
            }

            $slot->setData(new ArrayStruct($indexedUserPosts));
        }
    }

    private function getSalesChannelUseDatabaseAndFilesystem(string $salesChannelId)
    {
        return $this->systemConfigService->get('SolidInstagramElements.config.useDatabaseAndFilesystem', $salesChannelId);
    }

    private function getSalesChannelAccessToken(string $salesChannelId)
    {
        return $this->systemConfigService->get('SolidInstagramElements.config.accessToken', $salesChannelId);
    }

    private function getUserPosts(string $userId, Context $context): ?CmsSlotInstagramPostCollection
    {
        $criteria = new Criteria();
        $criteria
            ->addFilter(new EqualsFilter('userId', $userId))
            ->addSorting(new FieldSorting('timestamp', FieldSorting::DESCENDING));
        $userPosts = $this->cmsSlotInstagramPostRepository->search($criteria, $context);

        if (!$userPosts->getTotal()) {
            return null;
        }

        /**
         * @var CmsSlotInstagramPostCollection
         */
        return $userPosts->getEntities();
    }
}
