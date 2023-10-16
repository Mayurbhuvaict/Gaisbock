<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey\Storefront\Controller;

use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\Routing\Annotation\LoginRequired;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

/**
 * @internal
 */
#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class ShoppingExperienceController extends StorefrontController
{

    /**
     * @var EntityRepository
     */
    private $shoppingExperienceRepo;

    public function __construct(EntityRepository $shoppingExperienceRepo)
    {
        $this->shoppingExperienceRepo = $shoppingExperienceRepo;
    }

    #[Route(path: '/hl-store-survey/save-shopping-experience', name: 'frontend.checkout.saveShoppingExperience', defaults: ['_loginRequired' => true,'XmlHttpRequest' => true], methods: ['POST'])]
    public function saveShoppingExperience(Request $request, SalesChannelContext $context)
    {
        $customerId = $context->getCustomer() ? $context->getCustomer()->getId() : null;
        $salesChannelId = $context->getSalesChannel()->getId();

        $shoppingExperienceData = [
            'salesChannelId' => $salesChannelId,
            'customerId' => $customerId,
            'points' => intval($request->get('shopExperienceRating')),
            'comment' => $request->get('shopExperienceComment')
        ];

        $this->shoppingExperienceRepo->create([$shoppingExperienceData], Context::createDefaultContext());

        $successMessage = $this->trans('hlStoreSurvey.shopExperience.successMessage');

        return $this->renderStorefront('storefront/page/checkout/finish/shop-experience-modal-alert.html.twig', [
            'type' => 'success',
            'message' => $successMessage
        ]);
    }
}
