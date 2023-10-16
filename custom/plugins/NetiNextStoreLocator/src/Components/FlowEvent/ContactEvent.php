<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\FlowEvent;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use NetInventors\NetiNextStoreLocator\Events\StoreLocatorFilesAware;
use Shopware\Core\Content\Flow\Dispatching\Aware\ScalarValuesAware;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\EventData\ArrayType;
use Shopware\Core\Framework\Event\EventData\EntityType;
use Shopware\Core\Framework\Event\EventData\EventDataCollection;
use Shopware\Core\Framework\Event\EventData\MailRecipientStruct;
use Shopware\Core\Framework\Event\EventData\ScalarValueType;
use Shopware\Core\Framework\Event\MailAware;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @psalm-suppress DeprecatedInterface
 * FlowEventAware will have to be implemented manually which is now done
 * TODO: Remove suppression in SW 6.6
 */
class ContactEvent extends Event implements MailAware, ScalarValuesAware, StoreLocatorFilesAware
{
    public const EVENT_NAME = 'neti.store_locator.contact';

    private readonly string $shopName;

    public function __construct(
        private readonly SalesChannelContext $context,
        private readonly StoreEntity         $store,
        private readonly array               $values,
        private readonly array               $recipients,
        private readonly array               $files
    ) {
        $this->shopName = (string) $context->getSalesChannel()->getTranslation('name');
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('store', new EntityType(StoreDefinition::class))
            ->add('values', new ArrayType(new ScalarValueType(ScalarValueType::TYPE_STRING)))
            ->add('shopName', new ScalarValueType(ScalarValueType::TYPE_STRING));
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    public function getContext(): Context
    {
        return $this->context->getContext();
    }

    public function getMailStruct(): MailRecipientStruct
    {
        return new MailRecipientStruct(
            $this->recipients
        );
    }

    public function getSalesChannelId(): ?string
    {
        return $this->context->getSalesChannel()->getId();
    }

    public function getStore(): StoreEntity
    {
        return $this->store;
    }

    public function getContactValues(): array
    {
        return $this->values;
    }

    public function getValues(): array
    {
        return [
            'shopName' => $this->getShopName(),
            'values'   => $this->getContactValues(),
            'store'    => $this->getStore()->jsonSerialize(),
        ];
    }

    public function getShopName(): string
    {
        return $this->shopName;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
