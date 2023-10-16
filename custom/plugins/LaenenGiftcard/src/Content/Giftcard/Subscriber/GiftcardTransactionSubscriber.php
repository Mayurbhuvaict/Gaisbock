<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Content\Giftcard\Subscriber;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GiftcardTransactionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lae_giftcard.written' => 'onGiftcardWritten',
            'lae_giftcard_transaction.written' => 'onTransactionWritten',
        ];
    }

    public function onGiftcardWritten(EntityWrittenEvent $event): void
    {
        $ids = $event->getIds();
        if (!count($ids)) {
            return;
        }

        $this->connection->executeStatement(<<<SQL
UPDATE lae_giftcard
SET balance = initial_amount - (SELECT SUM(amount) FROM lae_giftcard_transaction WHERE giftcard_id = lae_giftcard.id)
WHERE id IN (:giftcardIds)
SQL, [
            'giftcardIds' => $ids,
        ], [
            'giftcardIds' => ArrayParameterType::STRING,
        ]);
    }

    public function onTransactionWritten(EntityWrittenEvent $event): void
    {
        $ids = $event->getIds();
        if (!count($ids)) {
            return;
        }

        $giftcardIds = $this->connection->fetchFirstColumn('
SELECT giftcard_id
FROM lae_giftcard_transaction
WHERE id IN (:transactionIds)
', [
            'transactionIds' => array_map(function (string $id) {
                return Uuid::fromHexToBytes($id);
            }, $ids),
        ], [
            'transactionIds' => ArrayParameterType::STRING,
        ]);

        $giftcardIds = array_values(array_unique(array_filter($giftcardIds)));
        if (!count($giftcardIds)) {
            return;
        }

        $this->connection->executeStatement(<<<SQL
UPDATE lae_giftcard
SET balance = initial_amount - (SELECT SUM(amount) FROM lae_giftcard_transaction WHERE giftcard_id = lae_giftcard.id)
WHERE id IN (:giftcardIds)
SQL, [
            'giftcardIds' => $giftcardIds,
        ], [
            'giftcardIds' => ArrayParameterType::STRING,
        ]);
    }
}
