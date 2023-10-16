<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Subscriber;

use Laenen\Giftcard\Event\GiftcardTemplateConfigEvent;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GiftcardTemplateDataSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $mediaRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GiftcardTemplateConfigEvent::class => 'onGiftcardTemplateConfig',
        ];
    }

    public function onGiftcardTemplateConfig(GiftcardTemplateConfigEvent $event): void
    {
        if ($event->getTemplate() === 'simple') {
            $this->handleSimple($event);
        }
    }

    private function handleSimple(GiftcardTemplateConfigEvent $event): void
    {
        $customFields = $event->getProduct()->getTranslation('customFields') ?? [];

        $data = $event->getConfig();

        if (!empty($customFields['laeGiftcardSimpleDesign'] ?? '')) {
            $data['coverMedia'] = $this->mediaRepository->search(
                new Criteria([$customFields['laeGiftcardSimpleDesign']]),
                $event->getContext()->getContext()
            )->first();
        }

        $event->setConfig($data);
    }
}
