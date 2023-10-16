<?php

namespace ICTECHNewsletterWithPromotion\Controller;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Neno\MarketingEssentials\Core\Event\CustomerRegisterWithPromotionEvent;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\RegisterController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route(defaults={"_routeScope" = {"storefront"}})
 */
class CustomerRegisterController extends StorefrontController {

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher, private readonly EntityRepository $registerPopupRepository, private readonly EntityRepository $customerRepository, private readonly RegisterController $registerController)
    {
    }

    #[Route(path: '/account/register/with-promotion', name: 'frontend.account.register.withPromotion', methods: ['POST'])]
    public function registerWithPromotion(Request $request, RequestDataBag $data, SalesChannelContext $context): Response
    {
        $popupId = $data->get('registerPopupId');

        $response = $this->registerController->register($request, $data, $context);

        if (strpos($response->getContent(), '__neno-register-form-has-errors') > -1) {
            // form errors in response
            return $response;
        }

        if ($popupId) {

            $popupCriteria = new Criteria();
            $popupCriteria->addAssociation('promotion');
            $popup = $this->registerPopupRepository->search(new Criteria([$popupId]), $context->getContext())->first();

            if (!$popup) {
                return $response;
            }

            if (!$popup->getPromotionActive()) {
                return $response;
            }

            if (!$promotion = $popup->promotion) {
                return $response;
            }

            $customerCriteria = new Criteria();
            $customerCriteria->addFilter(new EqualsFilter('email', $data->get('email')));
            $customer = $this->customerRepository->search($customerCriteria, $context->getContext())->first();

            if (!$customer) {
                throw new \Exception('No customer for' . $data->get('email') . ' email found');
            }

            $oldCustomFields = $customer->getCustomFields();

            if (!$oldCustomFields) {
                $oldCustomFields = [];
            }

            $newCustomFields = array_merge(
                $oldCustomFields,
                ['neno_nme_register_promotion_id' => $promotion->getId()]
            );

            $this->customerRepository->update([
                [
                    'id' => $customer->getId(),
                    'customFields' => $newCustomFields,
                ]
            ], $context->getContext());

            $event = new CustomerRegisterWithPromotionEvent($context, $customer);
            $event->setPromotionId($promotion->getId());

            $this->eventDispatcher->dispatch($event);
        }

        return $response;
    }
}
