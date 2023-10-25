<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Service;

use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class NewsletterPopupHandlePromotionService
{
    public function __construct(private readonly EntityRepository $newsletterRecipientRepository, private readonly EntityRepository $newsletterPopupRepository,private readonly EntityRepository $promotionRepository, private readonly PromotionCodeHelper $promotionCodeHelper)
    {
    }

    private function fetchPromotionById(string $promotionId, Context $context): ?PromotionEntity {
        if (!$promotionId) {
            return null;
        }

        $promotionCriteria = new Criteria([$promotionId]);
        $promotionCriteria->addAssociation('individualCodes');

        return $this->promotionRepository->search($promotionCriteria, $context)->first();
    }

    public function handlePromotion(string $email, string $popupId, context $context): ?string
    {
        $criteria = new Criteria([$popupId]);
        $criteria->addAssociation('promotion');

        $popup = $this->newsletterPopupRepository->search(new Criteria([$popupId]), $context)->first();
        if (!$popup) {
            throw new \Exception('No associated popup found');
        }

        if (!$popup->getPromotionActive()) {
//            return;
            throw new \Exception('No associated popup found');
        }
        $promotion = $popup->promotion;
        if (!$promotion) {

            throw new \Exception('No associated popup found');
        }

        $recipientCriteria = new Criteria();
        $recipientCriteria->addFilter(new EqualsFilter('email', $email));

        $recipient = $this->newsletterRecipientRepository->search($recipientCriteria, $context)->first();

        if (!$recipient) {
            throw new \Exception('No newsletter recipient found');
        }

        $oldCustomFields = $recipient->getCustomFields();

        if (!$oldCustomFields) {
            $oldCustomFields = [];
        }

        $newCustomFields = array_merge(
            $oldCustomFields,
            ['newsletter_popup_promotion_id' => $promotion->getId()]
        );

        $newPromotionCode = $this->fetchPromotionById($promotion->getId(), $context);
        $individualPromoCode = $this->promotionCodeHelper->handleIndividualPromotionCodes($newPromotionCode, $context);

        $this->newsletterRecipientRepository->update([
            [
                'id' => $recipient->getId(),
                'customFields' => $newCustomFields,
            ]
        ], $context);
        return $newPromotionCode->getCode();
    }
}
