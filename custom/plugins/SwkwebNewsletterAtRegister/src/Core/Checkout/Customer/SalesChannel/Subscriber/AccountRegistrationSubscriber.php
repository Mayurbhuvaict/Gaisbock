<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister\Core\Checkout\Customer\SalesChannel\Subscriber;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\CustomerEvents;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Shopware\Core\Checkout\Customer\Event\GuestCustomerRegisterEvent;
use Shopware\Core\Content\Newsletter\SalesChannel\AbstractNewsletterSubscribeRoute;
use Shopware\Core\Content\Newsletter\SalesChannel\NewsletterSubscribeRoute;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Event\DataMappingEvent;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Storefront\Framework\Routing\RequestTransformer;
use Swkweb\NewsletterAtRegister\Core\Content\NewsletterAtRegisterSubscription\NewsletterAtRegisterSubscriptionEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class AccountRegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AbstractNewsletterSubscribeRoute $newsletterSubscribeRoute,
        private readonly EntityRepository $subscriptionRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerEvents::MAPPING_REGISTER_CUSTOMER => 'onMapCustomerData',
            CustomerRegisterEvent::class => 'onCustomerRegister',
            GuestCustomerRegisterEvent::class => 'onCustomerRegister',
        ];
    }

    public function onMapCustomerData(DataMappingEvent $event): void
    {
        $customerData = $event->getOutput();

        if ($event->getInput()->getBoolean('newsletter')) {
            // Create entity
            $customerData['swkwebNewsletterAtRegisterSubscription'] = [];
        }

        $event->setOutput($customerData);
    }

    public function onCustomerRegister(CustomerRegisterEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request instanceof Request) {
            return;
        }

        $customer = $event->getCustomer();
        $subscription = $customer->getExtension('swkwebNewsletterAtRegisterSubscription');

        if (!$subscription instanceof NewsletterAtRegisterSubscriptionEntity) {
            return;
        }

        $dataBag = $this->createDataBagFromCustomer($customer);
        $dataBag->set('option', NewsletterSubscribeRoute::OPTION_SUBSCRIBE);
        $dataBag->set(
            'storefrontUrl',
            $request->attributes->get(RequestTransformer::STOREFRONT_URL),
        );

        $this->newsletterSubscribeRoute->subscribe($dataBag, $event->getSalesChannelContext(), true);

        $this->subscriptionRepository->delete([['id' => $subscription->getId()]], $event->getContext());
    }

    private function createDataBagFromCustomer(CustomerEntity $customer): RequestDataBag
    {
        $dataBag = new RequestDataBag();

        $dataBag->set('email', $customer->getEmail());
        $dataBag->set('salutationId', $customer->getSalutationId());
        $dataBag->set('title', $customer->getTitle());
        $dataBag->set('firstName', $customer->getFirstName());
        $dataBag->set('lastName', $customer->getLastName());

        $customerAddresses = $customer->getAddresses();
        if (!empty($customerAddresses)
            && !empty($customerAddresses->get($customer->getDefaultShippingAddressId()))) {
            $address = $customerAddresses->get($customer->getDefaultShippingAddressId());
            $dataBag->set('zipCode', $address->getZipCode());
            $dataBag->set('city', $address->getCity());
            $dataBag->set('street', $address->getStreet());
        }

        return $dataBag;
    }
}
