<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Doctrine\DBAL\Connection;
use Pluszwei\FaqManager\Core\Content\Category\DataAbstractionLayer\CategoryBreadcrumbUpdater;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\RetryableTransaction;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\ChildCountUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexingMessage;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\TreeUpdater;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Uuid\Uuid;

class CategoryIndexer extends EntityIndexer
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EntityRepository $repository,
        private readonly ChildCountUpdater $childCountUpdater,
        private readonly TreeUpdater $treeUpdater,
        private readonly IteratorFactory $iteratorFactory,
        private readonly CategoryBreadcrumbUpdater $breadcrumbUpdater,
        private readonly SeoUrlUpdater $seoUrlUpdater
    ) {
    }

    public function getName(): string
    {
        return 'pluszwei.faq.category.indexer';
    }

    public function iterate($offset): ?EntityIndexingMessage
    {
        $iterator = $this->iteratorFactory->createIterator($this->repository->getDefinition(), $offset);

        $ids = $iterator->fetch();

        if (empty($ids)) {
            return null;
        }

        return new EntityIndexingMessage(array_values($ids), $iterator->getOffset());
    }

    public function update(EntityWrittenContainerEvent $event): ?EntityIndexingMessage
    {
        $categoryEvent = $event->getEventByEntityName(CategoryDefinition::ENTITY_NAME);

        if (!$categoryEvent) {
            return null;
        }

        $ids = $categoryEvent->getIds();
        $idsWithChangedParentIds = [];
        foreach ($categoryEvent->getWriteResults() as $result) {
            if (!$result->getExistence()) {
                continue;
            }
            $state = $result->getExistence()->getState();

            if (isset($state['parent_id'])) {
                $ids[] = Uuid::fromBytesToHex($state['parent_id']);
            }

            $payload = $result->getPayload();
            if (\array_key_exists('parentId', $payload)) {
                if ($payload['parentId'] !== null) {
                    $ids[] = $payload['parentId'];
                }
                $idsWithChangedParentIds[] = $payload['id'];
            }
        }

        if (empty($ids)) {
            return null;
        }

        if ($idsWithChangedParentIds !== []) {
            // tree should be updated immediately
            $this->treeUpdater->batchUpdate(
                $ids,
                CategoryDefinition::ENTITY_NAME,
                $event->getContext()
            );
        }

        $children = $this->fetchChildren($ids, $event->getContext()->getVersionId());

        $ids = array_unique(array_merge($ids, $children));

        return new EntityIndexingMessage(array_values($ids), null, $event->getContext(), \count($ids) > 20);
    }

    public function handle(EntityIndexingMessage $message): void
    {
        $ids = $message->getData();

        $ids = array_unique(array_filter($ids));
        if (empty($ids)) {
            return;
        }

        $context = Context::createDefaultContext();

        RetryableTransaction::retryable($this->connection, function () use ($message, $ids, $context) {
            // listen to parent id changes
            $this->childCountUpdater->update(CategoryDefinition::ENTITY_NAME, $ids, $context);

            // listen to parent id changes
            $this->treeUpdater->batchUpdate($ids, CategoryDefinition::ENTITY_NAME, $context);

            // listen to name changes
            $this->breadcrumbUpdater->update($ids, $context);

            $this->seoUrlUpdater->update(CategorySeoUrlRoute::ROUTE_NAME, $ids);
        });
    }

    private function fetchChildren(array $categoryIds, string $versionId): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('DISTINCT LOWER(HEX(pluszwei_faq_category.id))');
        $query->from('pluszwei_faq_category');

        $wheres = [];
        foreach ($categoryIds as $id) {
            $key = 'path' . $id;
            $wheres[] = 'pluszwei_faq_category.path LIKE :' . $key;
            $query->setParameter($key, '%|' . $id . '|%');
        }

        $query->andWhere('(' . implode(' OR ', $wheres) . ')');
        $query->andWhere('pluszwei_faq_category.version_id = :version');
        $query->setParameter('version', Uuid::fromHexToBytes($versionId));

        return $query->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getTotal(): int
    {
        return $this->iteratorFactory->createIterator($this->repository->getDefinition())->fetchCount();
    }

    public function getDecorated(): EntityIndexer
    {
        throw new DecorationPatternException(static::class);
    }
}