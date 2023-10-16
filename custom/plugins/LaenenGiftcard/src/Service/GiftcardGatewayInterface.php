<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Service;

use Laenen\Giftcard\Struct\GiftcardStruct;
use Laenen\Giftcard\Struct\GiftcardStructCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

interface GiftcardGatewayInterface
{
    public function find(array $codes, SalesChannelContext $context): GiftcardStructCollection;

    public function getById(string $externalId, SalesChannelContext $context): ?GiftcardStruct;

    public function getFlatById(string $externalId, Context $context): ?GiftcardStruct;

    public function create(
        ?string $id,
        string $name,
        ?string $description,
        ?string $code,
        float $amount,
        ?string $originOrderId,
        ?string $originProductId,
        SalesChannelContext $context
    ): GiftcardStruct;

    public function addTransaction(
        string $type,
        string $externalId,
        string $orderId,
        float $amount,
        string $comment,
        string $salesChannelId,
        Context $context
    ): void;

    public function cancelTransaction(string $transactionId, string $reason, Context $context): void;
}
