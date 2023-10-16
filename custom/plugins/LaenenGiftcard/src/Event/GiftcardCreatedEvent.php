<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Event;

use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\EventData\EntityType;
use Shopware\Core\Framework\Event\EventData\EventDataCollection;
use Shopware\Core\Framework\Event\EventData\MailRecipientStruct;
use Shopware\Core\Framework\Event\MailAware;
use Shopware\Core\Framework\Event\OrderAware;
use Shopware\Core\Framework\Event\SalesChannelAware;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class GiftcardCreatedEvent implements MailAware, OrderAware, SalesChannelAware
{
    private const NAME = 'lae_giftcard.giftcard_created';

    public function __construct(
        private OrderEntity $order,
        private string $giftcardId,
        private SalesChannelContext $salesChannelContext
    )
    {
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('order', new EntityType(OrderDefinition::class));
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getMailStruct(): MailRecipientStruct
    {
        $customer = $this->order->getOrderCustomer();

        return new MailRecipientStruct([
            $customer->getEmail() => $customer->getFirstName() . ' ' . $customer->getLastName(),
        ]);
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelContext->getSalesChannelId();
    }

    public function getContext(): Context
    {
        return $this->salesChannelContext->getContext();
    }

    public function getOrderId(): string
    {
        return $this->order->getId();
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    public function getGiftcardId(): string
    {
        return $this->giftcardId;
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }
}
