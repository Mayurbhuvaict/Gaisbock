<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Service;

use Shopware\Core\Content\Mail\Service\AbstractMailService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\HttpFoundation\ParameterBag;

class SendPromotionMail
{
    public function __construct(private readonly AbstractMailService $mailService, private readonly EntityRepository $mailTemplateRepository)
    {
    }

    private function getMailTemplate($context) {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateType.technicalName', 'newsletter_popup_promotion'));
        $criteria->setLimit(1);

        return $this->mailTemplateRepository->search($criteria, $context)->first();
    }

    public function sendPromotionMail(object $promotion,string $salesChannelId, $context, $recipient): void
    {

        $mailTemplate = $this->getMailTemplate($context);
        $recipientMail = $recipient->get('email');
        $data = new ParameterBag();
        $data->set('recipient', $recipient);
        $data->set('promotion', $promotion);
        $data->set(
            'recipients',
            [
                $recipientMail => $recipientMail
            ]
        );
        $data->set('senderName', $mailTemplate->getSenderName());
        $data->set('contentHtml', $mailTemplate->getContentHtml());
        $data->set('contentPlain', $mailTemplate->getContentPlain());
        $data->set('subject', $mailTemplate->getSubject());
        $data->set('salesChannelId', $salesChannelId);

        $this->mailService->send(
            $data->all(),
            $context,
            $data->all()
        );
    }
}
