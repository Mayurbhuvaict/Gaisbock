<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Subscriber;

use Laenen\Giftcard\Core\Checkout\Event\GiftcardCartConvertedEvent;
use Laenen\Giftcard\Core\Checkout\RedeemGiftcardCollector;
use Laenen\Giftcard\Service\GiftcardGateway;
use Laenen\Giftcard\Struct\AppliedGiftcardStruct;
use Laenen\Giftcard\Struct\AppliedGiftcardStructCollection;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PlaceOrderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private GiftcardGateway $giftcardGateway,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CartConvertedEvent::class => 'onCartConverted',
            CheckoutOrderPlacedEvent::class => 'onOrderPlaced',
        ];
    }

    public function onCartConverted(CartConvertedEvent $event): void
    {
        $giftcards = $event->getCart()->getExtension(RedeemGiftcardCollector::GIFTCARDS);
        if (!$giftcards instanceof AppliedGiftcardStructCollection) {
            return;
        }

        $data = $event->getConvertedCart();

        foreach ($giftcards as $giftcard) {
            if (!$giftcard instanceof AppliedGiftcardStruct) {
                continue;
            }

            $code = $giftcard->getCode();

            $converted = [
                'externalId' => $giftcard->getExternalId(),
                'name' => $giftcard->getName(),
                'code' => $code,
                'type' => $giftcard->getType(),
                'appliedAmount' => $giftcard->getAppliedAmount(),
                'balanceBefore' => $giftcard->getBalance(),
                'balanceAfter' => $giftcard->getBalance() - $giftcard->getAppliedAmount(),
                'balanceInitial' => $giftcard->getInitialAmount(),
            ];

            $giftcardEvent = new GiftcardCartConvertedEvent(
                $event->getCart(),
                $event->getSalesChannelContext(),
                $giftcard,
                $converted
            );
            $this->dispatcher->dispatch($giftcardEvent);

            $data['customFields'][RedeemGiftcardCollector::GIFTCARDS][$code] = $giftcardEvent->getConverted();
        }

        $event->setConvertedCart($data);
    }

    public function onOrderPlaced(CheckoutOrderPlacedEvent $event): void
    {
        $customFields = $event->getOrder()->getCustomFields() ?? [];
        if (!array_key_exists(RedeemGiftcardCollector::GIFTCARDS, $customFields)) {
            return;
        }
        $giftcards = $customFields[RedeemGiftcardCollector::GIFTCARDS];

        foreach ($giftcards as $giftcard) {
            $this->giftcardGateway->addTransaction(
                $giftcard['type'],
                $giftcard['externalId'],
                $event->getOrderId(),
                $giftcard['appliedAmount'],
                'Shopware order ' . $event->getOrder()->getOrderNumber(),
                $event->getSalesChannelId(),
                $event->getContext()
            );
        }
    }
}
