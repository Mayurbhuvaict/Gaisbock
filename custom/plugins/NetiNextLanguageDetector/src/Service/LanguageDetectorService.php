<?php

declare(strict_types=1);

namespace NetInventors\NetiNextLanguageDetector\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainEntity;

class LanguageDetectorService
{
    public function __construct(
        private readonly EntityRepository $languageRepository,
        private readonly EntityRepository $domainRepository,
        private readonly EntityRepository $productVisibilityRepository,
        private readonly EntityRepository $localeRepository
    ) {
    }

    public function getLanguageByLocale(string $locale, Context $context): ?LanguageEntity
    {
        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('locale.code', $locale));
        $criteria->addAssociations([ 'locale.translations', 'translationCode' ]);

        /** @var ?LanguageEntity $result */
        $result = $this->languageRepository->search($criteria, $context)->first();

        return $result;
    }

    public function getSalesChannelsDomains(string $salesChannelId, Context $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addAssociations(
            [
                'language.locale',
                'language.locale.translations',
                'language.translationCode',
                'salesChannel',
            ]
        );
        $criteria->addFilter(new EqualsFilter('salesChannelId', $salesChannelId));

        return $this->domainRepository->search($criteria, $context);
    }

    public function getOtherSalesChannelsDomains(array $domains, string $salesChannelId, Context $context): array
    {
        $languageIds = [];

        if ([] !== $domains) {
            /** @var SalesChannelDomainEntity $domain */
            foreach ($domains as $domain) {
                $languageId               = $domain->getLanguageId();
                $languageIds[$languageId] = $languageId;
            }
        }

        $criteria = new Criteria();
        $criteria->addAssociations(
            [
                'language.locale',
                'language.locale.translations',
                'language.translationCode',
                'salesChannel.type',
            ]
        );

        $criteria->addFilter(new EqualsFilter('salesChannel.active', true));
        $criteria->addFilter(new EqualsFilter('salesChannel.type.id', Defaults::SALES_CHANNEL_TYPE_STOREFRONT));

        $criteria->addFilter(
            new NotFilter(
                MultiFilter::CONNECTION_AND,
                [ new EqualsAnyFilter('languageId', $languageIds) ]
            )
        );

        if ([] !== $languageIds) {
            $criteria->addFilter(
                new NotFilter(
                    MultiFilter::CONNECTION_AND,
                    [ new EqualsFilter('salesChannelId', $salesChannelId) ]
                )
            );
        }

        return $this->domainRepository->search($criteria, $context)->getElements();
    }

    public function getDefaultTargetDomain(string $languageId, Context $context, ?string $salesChannelId = null): ?SalesChannelDomainEntity
    {
        $criteria = new Criteria();
        $criteria->addAssociations(
            [
                'language.locale',
                'language.locale.translations',
                'language.translationCode',
                'salesChannel.type',
            ]
        );

        $criteria->addFilter(new EqualsFilter('languageId', $languageId));
        $criteria->addFilter(new EqualsFilter('salesChannel.active', true));
        $criteria->addFilter(new EqualsFilter('salesChannel.type.id', Defaults::SALES_CHANNEL_TYPE_STOREFRONT));

        if (is_string($salesChannelId)) {
            $criteria->addFilter(new EqualsFilter('salesChannel.id', $salesChannelId));
        }

        /** @var ?SalesChannelDomainEntity $result */
        $result = $this->domainRepository->search($criteria, $context)->first();

        return $result;
    }

    public function isDetailVisibleInSalesChannel(string $id, string $salesChannelId, Context $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $id));
        $criteria->addFilter(new EqualsFilter('salesChannelId', $salesChannelId));

        $result = $this->productVisibilityRepository->search($criteria, $context);

        return null !== $result->first();
    }

    public function getLanguageById(string $languageId, Context $context): ?LanguageEntity
    {
        $criteria = new Criteria([$languageId]);
        $criteria->addAssociation('locale');

        /** @var ?LanguageEntity $result */
        $result = $this->languageRepository->search($criteria, $context)->first();

        return $result;
    }
}
