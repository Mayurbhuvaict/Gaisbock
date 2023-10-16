<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\NavigationAssignment;


use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityDeletedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SwCategorySubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly EntityRepository $swCategoryRepository,
        private readonly UpdateNavigationPage $updateNavigationPage
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'category.written' => 'onCategoryWritten',
            'category.deleted' => 'onCategoryDeleted',
        ];
    }

    public function onCategoryDeleted(EntityDeletedEvent $event): void
    {
        $context = $event->getContext();
        $ids = $event->getIds();
        foreach ($ids as $id) {
            $this->updateNavigationPage->remove($id, $context);
        }
    }

    public function onCategoryWritten(EntityWrittenEvent $event): void
    {
        $payloads = $event->getPayloads();
        $ids = array_column($payloads, 'id');
        $criteria = new Criteria($ids);
        $context = $event->getContext();
        $categories = $this->swCategoryRepository->search($criteria, $context);
        /** @var CategoryEntity $category */
        foreach ($categories as $category) {
            if ($category->getType() !== 'faq') {
                continue;
            }

            if ($category->getActive()) {
                $this->updateNavigationPage->set($category->getId(), $context);
            }
        }
    }
}