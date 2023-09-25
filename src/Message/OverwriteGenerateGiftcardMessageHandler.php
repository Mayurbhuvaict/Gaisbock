<?php
declare(strict_types=1);

namespace Gaisbock\Message;

use Laenen\Giftcard\Message\GenerateGiftcardMessageHandler;
use Laenen\Giftcard\Message\GenerateGiftcardMessage;
use Laenen\Giftcard\Service\GiftcardGateway;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Laenen\Giftcard\Document\GiftcardDocumentGenerator;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\Mail\Service\AbstractMailService;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Adapter\Translation\AbstractTranslator;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\System\Locale\LanguageLocaleCodeProvider;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OverwriteGenerateGiftcardMessageHandler extends GenerateGiftcardMessageHandler
{
    public function __construct(
        private AbstractMailService $mailService,
        private GiftcardGateway $giftcardGateway,
        private EntityRepository $orderRepository,
        private GiftcardDocumentGenerator $giftcardDocumentGenerator,
        private SystemConfigService $systemConfigService,
        private EntityRepository $mailTemplateRepository,
        private AbstractTranslator $translator,
        private LanguageLocaleCodeProvider $languageLocaleProvider,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(GenerateGiftcardMessage $message)
    {
        $order = $this->getOrder($message->getOrderId(), $message->getSalesChannelContext()->getContext());
        $giftcard = $this->giftcardGateway->getById($message->getGiftcardId(), $message->getSalesChannelContext());

        if (!$order instanceof OrderEntity || !$giftcard instanceof GiftcardStruct) {
            return;
        }

        $salesChannelId = $message->getSalesChannelContext()->getSalesChannelId();

        $mailTemplate = $this->getMailTemplate(
            $this->systemConfigService->get('LaenenGiftcard.config.giftcardEmailTemplate', $salesChannelId),
            $message->getSalesChannelContext()->getContext()
        );

        if (!$mailTemplate) {
            return;
        }

        $this->translator->injectSettings(
            $salesChannelId,
            $message->getSalesChannelContext()->getLanguageId(),
            $this->languageLocaleProvider->getLocaleForLanguageId($message->getSalesChannelContext()->getLanguageId()),
            $message->getSalesChannelContext()->getContext()
        );

        $data = new DataBag();
        $data->set('recipients', [
            $order->getOrderCustomer()->getEmail() => $order->getOrderCustomer()->getFirstName() . ' '
                . $order->getOrderCustomer()->getLastName(),
        ]);
        $data->set('senderName', $mailTemplate->getTranslation('senderName'));
        $data->set('salesChannelId', $salesChannelId);

        $data->set('templateId', $mailTemplate->getId());
        $data->set('customFields', $mailTemplate->getCustomFields());
        $data->set('contentHtml', $mailTemplate->getTranslation('contentHtml'));
        $data->set('contentPlain', $mailTemplate->getTranslation('contentPlain'));
        $data->set('subject', $mailTemplate->getTranslation('subject'));
        $data->set('mediaIds', []);
//        $data->set('binAttachments', [
//            [
//                'content' => $this->giftcardDocumentGenerator->generate(
//                    null,
//                    $giftcard,
//                    $message->getSalesChannelContext()
//                ),
//                'fileName' => 'giftcard.pdf',
//                'mimeType' => 'application/pdf',
//            ],
//        ]);

        try {
            $this->mailService->send(
                $data->all(),
                $message->getSalesChannelContext()->getContext(),
                [
                    'order' => $order,
//                    'giftcard' => $giftcard,
                ]
            );
        } catch (\Exception $e) {
            $this->logger->error(
                "Could not send mail:\n"
                . $e->getMessage() . "\n"
                . 'Error Code:' . $e->getCode() . "\n"
                . "Template data: \n"
                . json_encode($data->all()) . "\n"
            );
        }

        $this->translator->resetInjection();
    }

    private function getOrder(string $orderId, Context $context): ?OrderEntity
    {
        return $this->orderRepository->search(
            new Criteria([$orderId]),
            $context
        )->first();
    }

    private function getMailTemplate(string $id, Context $context): ?MailTemplateEntity
    {
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('media.media');
        $criteria->setLimit(1);

        return $this->mailTemplateRepository
            ->search($criteria, $context)
            ->first();
    }
}
