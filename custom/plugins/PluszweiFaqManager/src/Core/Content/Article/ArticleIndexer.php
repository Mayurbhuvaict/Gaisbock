<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Article;


use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Seo\SeoUrlUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\RetryableTransaction;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexingMessage;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;

class ArticleIndexer extends EntityIndexer
{
    public function __construct(
        private readonly Connection $connection,
        private readonly IteratorFactory $iteratorFactory,
        private readonly SeoUrlUpdater $seoUrlUpdater,
        private readonly EntityRepository $repository
    ) {
    }

    public function getName(): string
    {
        return 'pluszwei.faq.article.indexer';
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
        $updates = $event->getPrimaryKeys(ArticleDefinition::ENTITY_NAME);

        if (empty($updates)) {
            return null;
        }

        return new EntityIndexingMessage(array_values($updates), null, $event->getContext());
    }

    public function handle(EntityIndexingMessage $message): void
    {
        $ids = $message->getData();
        $ids = array_unique(array_filter($ids));

        if (empty($ids)) {
            return;
        }

        RetryableTransaction::retryable($this->connection, function () use ($ids) {
            $this->seoUrlUpdater->update(ArticleSeoUrlRoute::ROUTE_NAME, $ids);
        });
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