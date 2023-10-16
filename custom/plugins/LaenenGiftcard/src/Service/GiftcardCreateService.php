<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Service;

use Laenen\Giftcard\Core\Checkout\RedeemGiftcardCollector;
use Laenen\Giftcard\Event\GiftcardCreatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceInterface;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceParameters;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class GiftcardCreateService
{

    public function __construct(
        private EntityRepository $orderRepository,
        private LoggerInterface $logger,
        private GiftcardGateway $giftcardGateway,
        private SalesChannelContextServiceInterface $salesChannelContextService,
        private EventDispatcherInterface $dispatcher,
        private SystemConfigService $systemConfigService
    ) {
    }

    public function handleTransaction(string $transactionId, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('transactions.id', $transactionId));

        self::addOrderCriteria($criteria);

        $order = $this->orderRepository->search($criteria, $context)->first();

        if (!$order instanceof OrderEntity) {
            $this->logger->warning('Transaction order not found', ['transactionId' => $transactionId]);

            return;
        }

        $this->createGiftcardsForOrder($order, $context);
    }

    public function handleOrder(string $orderId, Context $context): void
    {
        $criteria = new Criteria([$orderId]);

        self::addOrderCriteria($criteria);

        $order = $this->orderRepository->search($criteria, $context)->first();

        if (!$order instanceof OrderEntity) {
            $this->logger->warning('Order not found', ['orderId' => $orderId]);

            return;
        }

        $this->createGiftcardsForOrder($order, $context);
    }

    private function createGiftcardsForOrder(OrderEntity $order, Context $context): void
    {
        if ($this->systemConfigService->get('LaenenGiftcard.config.buyGiftcardActivateAction') === 'paymentPaid') {
            $transaction = $order->getTransactions() ? $order->getTransactions()->last() : null;
            if (!$transaction instanceof OrderTransactionEntity
                || ($transaction->getStateMachineState() ? $transaction->getStateMachineState()->getTechnicalName() : null)
                !== OrderTransactionStates::STATE_PAID
            ) {
                $this->logger->debug('Order is not paid (yet)', ['orderId' => $order->getId()]);

                return;
            }
        }

        $salesChannelContext = $this->salesChannelContextService->get(new SalesChannelContextServiceParameters(
            $order->getSalesChannelId(),
            Uuid::randomHex(),
            $order->getLanguageId(),
            $order->getCurrencyId(),
            null,
            $context
        ));

        /** @var OrderLineItemEntity[] $lineItems */
        $lineItems = $order->getLineItems()->filterByType(RedeemGiftcardCollector::GIFTCARDS);
        foreach ($lineItems as $lineItem) {
            $existing = $this->giftcardGateway->getById($lineItem->getId(), $salesChannelContext);
            if ($existing) {
                // Already exists
                continue;
            }

            $this->giftcardGateway->create(
                $lineItem->getId(),
                $lineItem->getLabel(),
                $lineItem->getDescription(),
                null,
                $lineItem->getTotalPrice(),
                $order->getId(),
                $lineItem->getReferencedId(),
                $salesChannelContext
            );

            $this->dispatcher->dispatch(new GiftcardCreatedEvent(
                $order,
                $lineItem->getId(),
                $salesChannelContext
            ));
        }
    }

    private static function addOrderCriteria(Criteria $criteria): void
    {
        $criteria
            ->addAssociation('lineItems')
            ->addAssociation('transactions.stateMachine');
    }
}
