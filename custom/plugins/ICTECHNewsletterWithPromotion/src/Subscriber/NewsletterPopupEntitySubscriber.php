<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\CacheClearer;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterPopupEntitySubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CacheClearer $cacheClearer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'newsletter_popup.written' => 'onDataWritten',
            'newsletter_popup.deleted' => 'onDataDeleted',
        ];
    }

    public function onDataWritten(EntityWrittenEvent $event): void
    {
        $this->cacheClearer->clear();
    }

    public function onDataDeleted(EntityWrittenEvent $event): void
    {
        $this->cacheClearer->clear();
    }
}
