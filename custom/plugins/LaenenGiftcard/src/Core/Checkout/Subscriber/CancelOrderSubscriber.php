<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Core\Checkout\Subscriber;

use Laenen\Giftcard\Service\GiftcardGateway;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\EntityNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\StateMachine\Event\StateMachineStateChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CancelOrderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $orderRepository,
        private EntityRepository $transactionRepository,
        private GiftcardGateway $giftcardGateway
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'state_machine.order.state_changed' => 'onOrderStateChange',
        ];
    }

    public function onOrderStateChange(StateMachineStateChangeEvent $event)
    {
        $orderStateCancelled = implode('.', [
            StateMachineStateChangeEvent::STATE_MACHINE_TRANSITION_SIDE_ENTER,
            OrderStates::STATE_MACHINE,
            OrderStates::STATE_CANCELLED,
        ]);

        if ($event->getStateEventName() !== $orderStateCancelled) {
            return;
        }

        $order = $this->orderRepository->search(
            new Criteria([$event->getTransition()->getEntityId()]),
            $event->getContext()
        )->first();

        if (!$order instanceof OrderEntity) {
            return;
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('orderId', $order->getId()));
        $transactions = $this->transactionRepository->search($criteria, $event->getContext());
        foreach ($transactions as $transaction) {
            try {
                $this->giftcardGateway->cancelTransaction(
                    $transaction->getId(),
                    'Order state: cancelled',
                    $event->getContext()
                );
            } catch (EntityNotFoundException $e) {
                // Ignore...
            }
        }
    }
}
