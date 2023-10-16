<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\CacheClearer;
use Shopware\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SystemConfigChangedSubscriber implements EventSubscriberInterface
{
    public static string  $PLUGIN_NAME = 'ICTECHNewsletterWithPromotion';

    public function __construct(private readonly CacheClearer $cacheClearer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigChangedEvent::class => 'onConfigChanged',
        ];
    }

    public function onConfigChanged(SystemConfigChangedEvent $event): void
    {
        if ($this->shouldHandle($event->getKey())) {
            $this->cacheClearer->clear();
        }
    }

    private function shouldHandle(string $key): bool
    {
        return str_starts_with($key, self::$PLUGIN_NAME . '.config');
    }
}
