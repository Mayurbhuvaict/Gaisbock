<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Subscriber;

use Laenen\Giftcard\Core\Checkout\RedeemGiftcardCollector;
use Laenen\Giftcard\Service\GiftcardCreateService;
use Laenen\Giftcard\Struct\GiftcardAmount;
use Shopware\Core\Checkout\Cart\Event\BeforeLineItemAddedEvent;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BuyGiftcardSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private SystemConfigService $systemConfigService,
        private GiftcardCreateService $giftcardCreateService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeLineItemAddedEvent::class => 'onBeforeLineItemAdded',
            CheckoutOrderPlacedEvent::class => 'onOrderPlaced',
            OrderEvents::ORDER_TRANSACTION_WRITTEN_EVENT => 'onOrderTransactionWritten',
        ];
    }

    public function onBeforeLineItemAdded(BeforeLineItemAddedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $lineItem = $event->getLineItem();

        $productId = $lineItem->getReferencedId();

        $postData = $request->get('lineItems');
        if (!is_array($postData)
            || !array_key_exists($productId, $postData)
            || !array_key_exists('giftcardAmount', $postData[$productId])
        ) {
            return;
        }

        $event->getCart()->remove($lineItem->getId());

        $giftcardAmount = $postData[$productId]['giftcardAmount'];

        $giftcardLineItem = new LineItem(
            Uuid::randomHex(),
            RedeemGiftcardCollector::GIFTCARDS,
            $lineItem->getReferencedId(),
            1
        );
        $giftcardLineItem->addExtension('giftcardAmount', new GiftcardAmount((float)$giftcardAmount));

        $event->getCart()->add($giftcardLineItem);
    }

    public function onOrderPlaced(CheckoutOrderPlacedEvent $event): void
    {
        if ($this->systemConfigService->get(
                'LaenenGiftcard.config.buyGiftcardActivateAction',
                $event->getSalesChannelId()
            ) !== 'orderPlaced'
        ) {
            return;
        }

        $this->giftcardCreateService->handleOrder($event->getOrderId(), $event->getContext());
    }


    public function onOrderTransactionWritten(EntityWrittenEvent $event): void
    {
        if ($this->systemConfigService->get('LaenenGiftcard.config.buyGiftcardActivateAction') !== 'paymentPaid') {
            return;
        }

        foreach ($event->getPayloads() as $payload) {
            $transactionId = $payload['id'];

            $this->giftcardCreateService->handleTransaction($transactionId, $event->getContext());
        }
    }
}
