<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Subscriber;

use Laenen\Giftcard\Event\GiftcardCreatedEvent;
use Laenen\Giftcard\Message\GenerateGiftcardMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GiftcardGenerateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents()
    {
        return [
            GiftcardCreatedEvent::class => 'onGiftcardCreated',
        ];
    }

    public function onGiftcardCreated(GiftcardCreatedEvent $event): void
    {
        $this->messageBus->dispatch(new GenerateGiftcardMessage(
            $event->getOrderId(),
            $event->getGiftcardId(),
            $event->getSalesChannelContext()
        ));
    }
}
