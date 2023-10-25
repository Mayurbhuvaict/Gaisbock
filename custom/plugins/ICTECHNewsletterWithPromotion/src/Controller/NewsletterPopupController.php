<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Controller;

use ICTECHNewsletterWithPromotion\Service\PromotionCodeHelper;
use ICTECHNewsletterWithPromotion\Service\NewsletterPopupHandlePromotionService;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Content\Newsletter\Aggregate\NewsletterRecipient\NewsletterRecipientEntity;
use Shopware\Core\Content\Newsletter\SalesChannel\AbstractNewsletterSubscribeRoute;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Captcha\Annotation\Captcha;
use Shopware\Storefront\Framework\Routing\RequestTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class NewsletterPopupController extends StorefrontController
{

    final public const SUBSCRIBE = 'subscribe';

    public function __construct(
        private readonly AbstractNewsletterSubscribeRoute      $subscribeRoute,
        private readonly NewsletterPopupHandlePromotionService $handlePromotion,
        private readonly PromotionCodeHelper                   $promotionCodeHelper,
        private readonly EntityRepository                                       $newsletterRepository
    )
    {
//        $this->newsletterRepository = $newsletterRepository;
    }


    #[Route(path: '/form/campaign/newsletter-coupon', name: 'frontend.ict.form.newsletter.popup.register.handle', defaults: ['XmlHttpRequest' => true, '_captcha' => true], methods: ['POST'])]
    public function handleSubscribe(Request $request, RequestDataBag $data, SalesChannelContext $context): JsonResponse
    {
        $response = array();
        try {
            $data->set('storefrontUrl', $request->attributes->get(RequestTransformer::STOREFRONT_URL));

            // Check if the email is already subscribed
            $existingSubscriber = $this->checkIfEmailExists($data->get('email'), $context);

            if ($existingSubscriber) {
                // If the email already exists, add an error message
                $response[] = [
                    'type' => 'danger',
                    'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                        'type' => 'danger',
                        'list' => [$this->trans('popup.message-default')],
                    ]),
                ];
            } else {
                // If the email is not subscribed, proceed with subscription
                $this->subscribeRoute->subscribe($data, $context, false);

                $email = $data->get('email');
                $popupId = $data->get('popupId');

                $promoCode = $this->handlePromotion->handlePromotion($email, $popupId, $context->getContext());
                $response[] = [
                    'type' => 'success',
                    'alert' => [($this->trans('popup.success') . ' ' . $promoCode)]
                ];
                $response[] = [
                    'type' => 'info',
                    'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                        'type' => 'info',
                        'list' => [$this->trans('newsletter.subscriptionPersistedInfo')],
                    ]),
                ];
            }
        } catch (ConstraintViolationException $exception) {
            $errors = [];
            foreach ($exception->getViolations() as $error) {
                $errors[] = $error->getMessage();
            }
            $response[] = [
                'type' => 'danger',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'danger',
                    'list' => $errors,
                ]),
            ];
        } catch (\Exception) {
            $response[] = [
                'type' => 'danger',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'danger',
                    'list' => [$this->trans('error.message-default')],
                ]),
            ];
        }
        return new JsonResponse($response);
    }

    private function checkIfEmailExists(string $email, SalesChannelContext $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('email', $email));
        $criteria->setLimit(1);

        $existingSubscriber = $this->newsletterRepository->search($criteria, $context->getContext());

        return $existingSubscriber->getTotal() > 0;
    }
}