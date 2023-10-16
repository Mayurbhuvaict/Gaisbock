<?php

namespace Neno\MarketingEssentials\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\CacheClearer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;

class TabsEntitySubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly CacheClearer $cacheClearer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'neno_marketing_essentials_tabs.written' => 'onDataWritten',
            'neno_marketing_essentials_tabs.deleted' => 'onDataDeleted',
        ];
    }

    public function onDataWritten(EntityWrittenEvent $event):void {
        $this->cacheClearer->clear();
    }

    public function onDataDeleted(EntityWrittenEvent $event):void {
        $this->cacheClearer->clear();
    }
}
