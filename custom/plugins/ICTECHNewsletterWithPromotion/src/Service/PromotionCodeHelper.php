<?php

declare(strict_types=1);

namespace ICTECHNewsletterWithPromotion\Service;

use ICTECHNewsletterWithPromotion\Core\Content\ReservedIndividualCode\ReservedIndividualCodeCollection;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Checkout\Promotion\Util\PromotionCodeService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class PromotionCodeHelper
{
    public function __construct(
        private readonly EntityRepository $individualCodesRepository,
        private readonly EntityRepository $reservedIndividualCodeRepository,
        private readonly PromotionCodeService $codeService
    ) {
    }

    /**
     * Generates and upserts a new batch of individual promotion codes.
     * The first entry will be reserved and its code returned.
     *
     * @return string Returns the first generated code
     */
    private function generateNewIndividualCodes(
        PromotionEntity $promotion,
        int $amount,
        Context $context
    ): string {
        $newCodes = $this->codeService->generateIndividualCodes(
            $promotion->getIndividualCodePattern(),
            $amount,
            ($promotion->getIndividualCodes() !== null)
                ? $promotion->getIndividualCodes()->getCodeArray()
                : []
        );

        $codeEntries = $this->prepareCodeEntities(
            $promotion->getId(),
            $newCodes
        );
        $codeEntries[0]['id'] = Uuid::randomHex();

        $this->individualCodesRepository->upsert($codeEntries, $context);
        $this->reserveIndividualCode(
            $promotion->getId(),
            $codeEntries[0]['id'],
            $context
        );

        return array_values($newCodes)[0];
    }

    private function reserveIndividualCode(
        string $promotionId,
        string $promotionIndividualCodeId,
        Context $context
    ): void {
        $this->reservedIndividualCodeRepository->create([
            [
                'promotionId' => $promotionId,
                'promotionIndividualCodeId' => $promotionIndividualCodeId,
            ]
        ], $context);
    }

    public function handleIndividualPromotionCodes(
        PromotionEntity $promotion,
        Context $context
    ): void
    {
        if ($promotion->isUseIndividualCodes()) {
            $individualCodesEntities = $promotion->getIndividualCodes();

            if ($individualCodesEntities === null) {
                $nextFreeCode = $this->generateNewIndividualCodes(
                    $promotion,
                    10,
                    $context
                );
            } else {
                $reservedCodeCriteria = new Criteria();
                $reservedCodeCriteria->addFilter(new EqualsFilter('promotionId', $promotion->getId()));

                /**
                 * @var ReservedIndividualCodeCollection $reservedCodeEntities
                 */
                $reservedCodeEntities = $this->reservedIndividualCodeRepository
                    ->search($reservedCodeCriteria, $context);

                $reservedIndividualCodeIds = [];

                foreach ($reservedCodeEntities as $reservedCodeEntity) {
                    $reservedIndividualCodeIds[$reservedCodeEntity->getPromotionIndividualCodeId()] = true;
                }

                $unusedCodeEntity = null;

                foreach ($individualCodesEntities as $codeEntity) {
                    $payload = $codeEntity->getPayload();
                    if (
                        // If we have any payload, the individual code is treated as redeemed by shopware.
                        $payload === null &&
                        // Otherwise, we check if the code is already reserved by us
                        !array_key_exists($codeEntity->getId(), $reservedIndividualCodeIds)
                    ) {
                        $unusedCodeEntity = $codeEntity;
                        break;
                    }
                }
                if ($unusedCodeEntity === null) {
                    // Generate new codes and pick the first one
                    $nextFreeCode = $this->generateNewIndividualCodes(
                        $promotion,
                        10,
                        $context
                    );
                } else {
                    $this->reserveIndividualCode(
                        $promotion->getId(),
                        $unusedCodeEntity->getId(),
                        $context
                    );
                    $nextFreeCode = $unusedCodeEntity->getCode();
                }

            }
            $promotion->setCode($nextFreeCode);
        }
    }

    private function prepareCodeEntities(
        string $promotionId,
        array $codes
    ): array {
        return array_values(array_map(static fn ($code) => [
            'promotionId' => $promotionId,
            'code' => $code,
        ], $codes));
    }
}
