<?php
declare(strict_types=1);

namespace Laenen\Giftcard\Service;

use Laenen\Giftcard\Content\Giftcard\Aggregate\GiftcardTransaction\GiftcardTransactionEntity;
use Laenen\Giftcard\Content\Giftcard\GiftcardEntity;
use Laenen\Giftcard\Exception\UnsupportedGiftcardType;
use Laenen\Giftcard\Struct\GiftcardStruct;
use Laenen\Giftcard\Struct\GiftcardStructCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\EntityNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\OrFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class GiftcardGateway implements GiftcardGatewayInterface
{
    public const TYPE = 'local';

    public function __construct(
        private EntityRepository $giftcardRepository,
        private EntityRepository $giftcardTransactionRepository,
        private GiftcardCodeService $giftcardCodeService
    ) {
    }

    public function find(array $codes, SalesChannelContext $context): GiftcardStructCollection
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsAnyFilter('code', $codes),
            new OrFilter([
                new EqualsFilter('salesChannelId', null),
                new EqualsFilter('salesChannelId', $context->getSalesChannelId()),
            ]),
        );

        $giftcards = $this->giftcardRepository->search($criteria, $context->getContext());

        $structs = new GiftcardStructCollection();

        /** @var GiftcardEntity $giftcard */
        foreach ($giftcards as $giftcard) {
            $structs->add(GiftcardStruct::createFromGiftcard($giftcard));
        }

        return $structs;
    }

    public function getById(string $externalId, SalesChannelContext $context): ?GiftcardStruct
    {
        $criteria = new Criteria([$externalId]);
        $criteria->addFilter(
            new OrFilter([
                new EqualsFilter('salesChannelId', null),
                new EqualsFilter('salesChannelId', $context->getSalesChannelId()),
            ]),
        );

        $giftcard = $this->giftcardRepository->search($criteria, $context->getContext())->first();

        return $giftcard instanceof GiftcardEntity ? GiftcardStruct::createFromGiftcard($giftcard) : null;
    }

    public function getFlatById(string $externalId, Context $context): ?GiftcardStruct
    {
        $criteria = new Criteria([$externalId]);

        $giftcard = $this->giftcardRepository->search($criteria, $context)->first();

        return $giftcard instanceof GiftcardEntity ? GiftcardStruct::createFromGiftcard($giftcard) : null;
    }

    public function create(
        ?string $id,
        string $name,
        ?string $description,
        ?string $code,
        float $amount,
        ?string $originOrderId,
        ?string $originProductId,
        SalesChannelContext $context
    ): GiftcardStruct {
        if (!$code) {
            $code = $this->giftcardCodeService->generate($context->getSalesChannelId());
        }

        $id = $id ?? Uuid::randomHex();
        $salesChannelId = $context->getSalesChannelId();
        $context->getContext()->scope(
            Context::SYSTEM_SCOPE,
            function (Context $context) use ($id, $name, $description, $code, $amount, $originOrderId, $originProductId, $salesChannelId): void {
                $this->giftcardRepository->create([
                    [
                        'id' => $id ?? Uuid::randomHex(),
                        'name' => $name,
                        'description' => $description,
                        'code' => $code,
                        'balance' => $amount,
                        'initialAmount' => $amount,
                        'originOrderId' => $originOrderId,
                        'originProductId' => $originProductId,
                        'currencyId' => $context->getCurrencyId(),
                        'languageId' => $context->getLanguageId(),
                        'salesChannelId' => $salesChannelId,
                    ],
                ], $context);
            }
        );

        $giftcard = $this->getById($id, $context);
        if (!$giftcard instanceof GiftcardStruct) {
            throw new \RuntimeException('Error during giftcard create');
        }

        return $giftcard;
    }

    public function addTransaction(
        string $type,
        string $externalId,
        string $orderId,
        float $amount,
        string $comment,
        string $salesChannelId,
        Context $context
    ): void {
        if ($type !== self::TYPE) {
            throw new UnsupportedGiftcardType($type, 'pay');
        }

        $this->giftcardTransactionRepository->create([
            [
                'id' => Uuid::randomHex(),
                'giftcardId' => $externalId,
                'amount' => $amount,
                'orderId' => $orderId,
                'comment' => $comment,
            ],
        ], $context);
    }

    public function cancelTransaction(
        string $transactionId,
        string $reason,
        Context $context
    ): void {
        $transaction = $this->giftcardTransactionRepository->search(new Criteria([$transactionId]), $context)->first();
        if (!$transaction instanceof GiftcardTransactionEntity) {
            throw new EntityNotFoundException('lae_giftcard_transaction', $transactionId);
        }

        $this->giftcardTransactionRepository->create([
            [
                'id' => Uuid::randomHex(),
                'giftcardId' => $transaction->getGiftcardId(),
                'amount' => -$transaction->getAmount(),
                'orderId' => $transaction->getOrderId(),
                'comment' => 'Revert transaction ' . $transaction->getId() . ': ' . $reason,
            ],
        ], $context);
    }
}
