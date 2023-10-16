<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Controller;

use Shopware\Core\Content\ContactForm\SalesChannel\AbstractContactFormRoute;
use Shopware\Core\Content\Newsletter\SalesChannel\AbstractNewsletterSubscribeRoute;
use Shopware\Core\Content\Newsletter\SalesChannel\AbstractNewsletterUnsubscribeRoute;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Framework\Routing\RequestTransformer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Storefront\Framework\Captcha\Annotation\Captcha;
use Symfony\Component\HttpFoundation\JsonResponse;
use Neno\MarketingEssentials\Service\NewsletterPopupHandlePromotionService;
use Twig\Environment;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class NewsletterPopupController extends StorefrontController {

    final public const SUBSCRIBE = 'subscribe';

    public function __construct(private readonly AbstractNewsletterSubscribeRoute $subscribeRoute, private readonly NewsletterPopupHandlePromotionService $handlePromotion)
    {
    }

    #[Route(path: '/form/campaign/newsletter-coupon', name: 'frontend.neno.form.newsletter.popup.register.handle', defaults: ['XmlHttpRequest' => true, '_captcha' => true], methods: ['POST'])]
    public function handleSubscribe(Request $request, RequestDataBag $data, SalesChannelContext $context): JsonResponse
    {
        try {
            $data->set('storefrontUrl', $request->attributes->get(RequestTransformer::STOREFRONT_URL));

            $this->subscribeRoute->subscribe($data, $context, false);

            $email = $data->get('email');
            $popupId = $data->get('popupId');

            $this->handlePromotion->handlePromotion($email, $popupId, $context->getContext());

            $response[] = [
                'type' => 'success',
                'alert' => $this->trans('newsletter.subscriptionPersistedSuccess'),
            ];
            $response[] = [
                'type' => 'info',
                'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                    'type' => 'info',
                    'list' => [$this->trans('newsletter.subscriptionPersistedInfo')],
                ]),
            ];
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
}
