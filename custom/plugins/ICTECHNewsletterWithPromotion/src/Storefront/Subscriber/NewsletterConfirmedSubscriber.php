<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Storefront\Subscriber;

use ICTECHNewsletterWithPromotion\Service\PromotionCodeHelper;
use ICTECHNewsletterWithPromotion\Service\SendPromotionMail;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Content\Newsletter\Event\NewsletterConfirmEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterConfirmedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityRepository $promotionRepository, private readonly PromotionCodeHelper $promotionCodeHelper, private readonly SendPromotionMail $sendPromotionMail)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewsletterConfirmEvent::class => 'listenToConfirmation'
        ];
    }

    private function fetchPromotionById(string $promotionId, Context $context): ?PromotionEntity {
        if (!$promotionId) {
            return null;
        }

        $promotionCriteria = new Criteria([$promotionId]);
        $promotionCriteria->addAssociation('individualCodes');

        return $this->promotionRepository->search($promotionCriteria, $context)->first();
    }

    public function listenToConfirmation(NewsletterConfirmEvent $event): void {

        $recipient = $event->getNewsletterRecipient();
        $recipientCustomFields = $recipient->getCustomFields();

        if ($recipientCustomFields === null) {
            return;
        }

        $promotionId = $recipientCustomFields['newsletter_popup_promotion_id'];
        if (!$promotionId) { return; }

        $promotion = $this->fetchPromotionById($promotionId, $event->getContext());

        if (!$promotion) {
            return;
            // throw new \Exception('No promotion found for this recipient');
        }

        $this->promotionCodeHelper->handleIndividualPromotionCodes($promotion, $event->getContext());

        $this->sendPromotionMail
            ->sendPromotionMail(
                $promotion,
                $event->getSalesChannelId(),
                $event->getContext(),
                $recipient);
    }
}
