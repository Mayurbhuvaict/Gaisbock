<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Subscriber;

use NetInventors\NetiNextStoreLocator\Constants\FlowConstants;
use NetInventors\NetiNextStoreLocator\Events\StoreLocatorFilesAware;
use Shopware\Core\Content\Flow\Events\FlowSendMailActionEvent;
use Shopware\Core\Framework\Event\BusinessEventCollector;
use Shopware\Core\Framework\Event\BusinessEventCollectorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BusinessEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BusinessEventCollector $businessEventCollector
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BusinessEventCollectorEvent::NAME => [ 'addEvent', 1000 ],
            FlowSendMailActionEvent::class    => 'onSendMailAction',
        ];
    }

    public function addEvent(BusinessEventCollectorEvent $event): void
    {
        $collection = $event->getCollection();
        $flowEvents = FlowConstants::EVENT_CLASSES;

        foreach ($flowEvents as $flowEvent) {
            $definition = $this->businessEventCollector->define($flowEvent);

            if (!$definition) {
                return;
            }

            $collection->set($definition->getName(), $definition);
        }
    }

    public function onSendMailAction(FlowSendMailActionEvent $event): void
    {
        $flowEvent = $event->getStorableFlow();
        /** @var array|null $files */
        $files = $flowEvent->getData(StoreLocatorFilesAware::STORE_KEY);

        if (is_array($files)) {
            $event->getDataBag()->set('binAttachments', $this->getAttachments($files));
        }
    }

    private function getAttachments(array $files): array
    {
        $attachments = [];

        /** @var array<string, mixed> $file */
        foreach ($files as $file) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $file['file'];
            /** @var string $name */
            $name = $file['name'];

            $attachments[] = [
                'content'  => $uploadedFile->getContent(),
                'fileName' => $name,
                'mimeType' => $uploadedFile->getMimeType(),
            ];
        }

        return $attachments;
    }
}
