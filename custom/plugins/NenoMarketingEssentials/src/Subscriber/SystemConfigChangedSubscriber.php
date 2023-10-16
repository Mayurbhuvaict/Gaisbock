<?php

namespace Neno\MarketingEssentials\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\CacheClearer;
use Shopware\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SystemConfigChangedSubscriber implements EventSubscriberInterface {

    public function __construct(private readonly CacheClearer $cacheClearer)
    {
    }

    static string $PLUGIN_NAME = 'NenoMarketingEssentials';

    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigChangedEvent::class => 'onConfigChanged'
        ];
    }

    private function shouldHandle(string $key): bool {
        return (str_starts_with($key, self::$PLUGIN_NAME . '.config'));
    }

    public function onConfigChanged(SystemConfigChangedEvent $event):void {
        if ($this->shouldHandle($event->getKey())) {
            $this->cacheClearer->clear();
        }
    }
}
