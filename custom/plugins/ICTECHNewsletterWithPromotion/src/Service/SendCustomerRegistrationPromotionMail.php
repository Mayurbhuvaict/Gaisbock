<?php declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Content\Mail\Service\AbstractMailService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\HttpFoundation\ParameterBag;

class SendCustomerRegistrationPromotionMail
{
    public function __construct(private readonly AbstractMailService $mailService, private readonly EntityRepository $mailTemplateRepository)
    {
    }

    private function getMailTemplate($context) {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateType.technicalName', 'neno_register_promotion'));
        $criteria->setLimit(1);

        return $this->mailTemplateRepository->search($criteria, $context)->first();
    }

    public function sendRegistrationPromotionMail(PromotionEntity $promotion,string $salesChannelId,Context $context,CustomerEntity $customer)
    {
        $mailTemplate = $this->getMailTemplate($context);
        $customerMail = $customer->get('email');

        $data = new ParameterBag();
        $data->set('customer', $customer);
        $data->set('promotion', $promotion);
        $data->set(
            'recipients',
            [
                $customerMail => $customerMail
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
