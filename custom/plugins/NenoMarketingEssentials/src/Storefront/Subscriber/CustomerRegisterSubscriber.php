<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Neno\MarketingEssentials\Core\Event\CustomerRegisterWithPromotionEvent;
use Neno\MarketingEssentials\Service\PromotionCodeHelper;
use Neno\MarketingEssentials\Service\SendCustomerRegistrationPromotionMail;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerRegisterSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityRepository $promotionRepository, private readonly EntityRepository $customerRepository, private readonly PromotionCodeHelper $promotionCodeHelper, private readonly SendCustomerRegistrationPromotionMail $sendCustomerRegistrationPromotionMail)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerRegisterWithPromotionEvent::class => 'listenToCustomerRegistration',
            CustomerRegisterEvent::class => 'handleDoubleOptInRegistration'
        ];
    }

    private function setCustomerDoubleOptInFlag(CustomerEntity $customer, Context $context) {
        $oldCustomFields = $customer->getCustomFields();

        if (!$oldCustomFields) {
            $oldCustomFields = [];
        }

        $newCustomFields = array_merge(
            $oldCustomFields,
            ['neno_nme_register_promotion_doi' => true]
        );

        $this->customerRepository->update([
            [
                'id' => $customer->getId(),
                'customFields' => $newCustomFields,
            ]
        ], $context);
    }

    private function customerHasDoubleOptInPromotion(CustomerEntity $customer): bool {
        $customFields = $customer->getCustomFields();

        if (!$customer->getDoubleOptInRegistration() || $customFields === null) {
            return false;
        }

        return (
            array_key_exists('neno_nme_register_promotion_id', $customFields) &&
            $customFields['neno_nme_register_promotion_id'] &&
            array_key_exists('neno_nme_register_promotion_doi', $customFields)
        );
    }

    private function getPromotionIdFromCustomerCustomFields(CustomerEntity $customer): ?string {
        $customerCustomFields = $customer->getCustomFields();

        if ($customerCustomFields === null) {
            return null;
        }

        return $customerCustomFields['neno_nme_register_promotion_id'];
    }

    private function fetchPromotionById(string $promotionId, Context $context): ?PromotionEntity {
        if (!$promotionId) {
            return null;
        }

        $promotionCriteria = new Criteria([$promotionId]);
        $promotionCriteria->addAssociation('individualCodes');

        return $this->promotionRepository->search($promotionCriteria, $context)->first();
    }

    public function listenToCustomerRegistration(CustomerRegisterWithPromotionEvent $event):void {

        $customer = $event->getCustomer();

        if ($customer->getDoubleOptInRegistration()) {
            $this->setCustomerDoubleOptInFlag($customer, $event->getContext());
            // Wait until we receive the doi confirmation
            return;
        }

        $promotionId = $event->getPromotionId();

        if (!$promotionId) {
            $promotionId = $this->getPromotionIdFromCustomerCustomFields($customer);
        }

        if (!$promotionId) { return; }

        $promotion = $this->fetchPromotionById($promotionId, $event->getContext());

        if (!$promotion) { return; }

        $this->promotionCodeHelper
            ->handleIndividualPromotionCodes($promotion, $event->getContext());

        $this->sendCustomerRegistrationPromotionMail
            ->sendRegistrationPromotionMail(
                $promotion,
                $event->getSalesChannelId(),
                $event->getContext(),
                $customer);
    }

    public function handleDoubleOptInRegistration(CustomerRegisterEvent $event) {
        $customer = $event->getCustomer();

        if (!$this->customerHasDoubleOptInPromotion($customer)) {
            return;
        }

        $promotionId = $this->getPromotionIdFromCustomerCustomFields($customer);

        if (!$promotionId) { return; }

        $promotion = $this->fetchPromotionById($promotionId, $event->getContext());

        if (!$promotion) { return; }

        $this->promotionCodeHelper
            ->handleIndividualPromotionCodes($promotion, $event->getContext());

        $this->sendCustomerRegistrationPromotionMail
            ->sendRegistrationPromotionMail(
                $promotion,
                $event->getSalesChannelId(),
                $event->getContext(),
                $customer);
    }
}
