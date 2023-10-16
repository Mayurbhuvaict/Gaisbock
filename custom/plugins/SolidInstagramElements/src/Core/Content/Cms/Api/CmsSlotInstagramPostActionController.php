<?php declare(strict_types=1);

namespace StudioSolid\InstagramElements\Core\Content\Cms\Api;

use Shopware\Core\Framework\Context;
use StudioSolid\InstagramElements\Core\Content\Cms\Service\CmsSlotInstagramPostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"api"}})
 */
class CmsSlotInstagramPostActionController extends AbstractController
{
    private CmsSlotInstagramPostService $cmsSlotInstagramPostService;

    public function __construct(
        CmsSlotInstagramPostService $cmsSlotInstagramPostService
    ) {
        $this->cmsSlotInstagramPostService = $cmsSlotInstagramPostService;
    }

    /**
     * @Route("api/_action/solid-ie/fetch-and-store-latest-posts", name="api.action.solid-ie.fetch-and-store-latest-posts", methods={"POST"})
     */
    public function fetchAndStoreLatestPosts(Request $request, Context $context): Response
    {
        $this->cmsSlotInstagramPostService->fetchAndStoreLatestPosts();

        return new Response();
    }
}
