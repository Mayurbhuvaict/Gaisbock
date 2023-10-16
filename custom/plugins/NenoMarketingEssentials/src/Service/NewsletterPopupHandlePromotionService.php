<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class NewsletterPopupHandlePromotionService
{
    public function __construct(private readonly EntityRepository $newsletterRecipientRepository, private readonly EntityRepository $newsletterPopupRepository)
    {
    }

    public function handlePromotion(string $email, string $popupId, context $context): void
    {
        $criteria = new Criteria([$popupId]);
        $criteria->addAssociation('promotion');

        $popup = $this->newsletterPopupRepository->search(new Criteria([$popupId]), $context)->first();

        if (!$popup) {
            throw new \Exception('No associated popup found');
        }

        if (!$popup->getPromotionActive()) {
            return;
        }

        if (!$promotion = $popup->promotion) {
            return;
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
            ['neno_nme_newsletter_promotion_id' => $promotion->getId()]
        );

        $this->newsletterRecipientRepository->update([
            [
                'id' => $recipient->getId(),
                'customFields' => $newCustomFields,
            ]
        ], $context);
    }
}
