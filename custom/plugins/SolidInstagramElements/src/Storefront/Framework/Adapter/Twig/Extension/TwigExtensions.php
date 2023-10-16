<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Storefront\Framework\Adapter\Twig\Extension;

use StudioSolid\InstagramElements\Core\Content\Cms\Aggregate\CmsSlotInstagramPost\CmsSlotInstagramPostEntity;
use StudioSolid\InstagramElements\Core\Content\Cms\Service\CmsSlotInstagramPostService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension
{
    private CmsSlotInstagramPostService $cmsSlotInstagramPostService;

    public function __construct(
        CmsSlotInstagramPostService $cmsSlotInstagramPostService
    ) {
        $this->cmsSlotInstagramPostService = $cmsSlotInstagramPostService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('solidIeGetCmsSlotInstagramPostMediaUrl', [$this, 'getCmsSlotInstagramPostMediaUrl']),
        ];
    }

    public function getCmsSlotInstagramPostMediaUrl(CmsSlotInstagramPostEntity $postItem): ?string
    {
        return $this->cmsSlotInstagramPostService->getMediaUrl($postItem->getUserId(), $postItem->getPostId());
    }
}
