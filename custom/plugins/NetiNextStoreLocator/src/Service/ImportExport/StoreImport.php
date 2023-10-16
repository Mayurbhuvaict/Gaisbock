<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service\ImportExport;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @psalm-type SalesChannelRecord = array{
 *     id: string,
 * }
 *
 * @psalm-type StoreRecord = array{
 *    id: string,
 *    countryId: string|null,
 *    country?: array{iso: string}|null,
 *    countryStateId?: string|null,
 *    countryState?: array{shortCode: string}|null,
 *    excludeFromSync: bool|empty,
 *    label: string,
 *    showAlways: string,
 *    salesChannels?: SalesChannelRecord[]
 * }
 */
class StoreImport
{
    private const COUNTRY_ISOS = [
        '2'  => 'DE',
        '3'  => 'AE',
        '4'  => 'AU',
        '5'  => 'BE',
        '7'  => 'DK',
        '8'  => 'FI',
        '9'  => 'FR',
        '10' => 'GR',
        '11' => 'GB',
        '12' => 'IE',
        '13' => 'IS',
        '14' => 'IT',
        '15' => 'JP',
        '16' => 'CA',
        '18' => 'LU',
        '20' => 'NA',
        '21' => 'NL',
        '22' => 'NO',
        '23' => 'AT',
        '24' => 'PT',
        '25' => 'SE',
        '26' => 'CH',
        '27' => 'ES',
        '28' => 'US',
        '29' => 'LI',
        '30' => 'PL',
        '31' => 'HU',
        '32' => 'TR',
        '33' => 'CZ',
        '34' => 'SK',
        '35' => 'RO',
        '36' => 'BR',
        '37' => 'IL',
    ];

    /** @var array<string, string> */
    private array $cachedCountryIds       = [];

    /** @var array<string, string> */
    private array $cachedIsoCountryIds    = [];

    /** @var array<string, string> */
    private array $cachedSalesChannelIds  = [];

    /** @var string[] */
    private array $defaultSalesChannelIds = [];

    public function __construct(
        private readonly EntityRepository $repository,
        private readonly EntityRepository $salesChannelRepository,
        private readonly EntityRepository $countryRepository,
        private readonly EntityRepository $countryStateRepository
    ) {
    }

    /**
     * @psalm-param StoreRecord $data
     */
    public function prepareDataForImport(array $data, Context $context): array
    {
        if (is_string($data['countryId']) && Uuid::isValid($data['countryId'])) {
            $data['countryId'] = $this->getCountryId($data['countryId'], $context);
        }

        $data['id'] = $this->createStoreId($data, $context);

        /**
         * Due to the condition above data must be an array
         *
         * @psalm-suppress MixedArgument
         */
        $data['showAlways'] = $this->setShowAlways($data);

        if (isset($data['salesChannels'])) {
            $data['salesChannels'] = $this->assignSalesChannel($data['salesChannels'], $context);
        }

        if (isset($data['countryId'])) {
            $data['countryId'] = $this->assignCountryIdByIso($data, $context);

            if (isset($data['country'])) {
                unset($data['country']);
            }

            if (isset($data['countryState'])) {
                $data['countryStateId'] = $this->assignCountryStateByShortCode($data, $context);
                unset($data['countryState']);
            }
        }

        return $data;
    }

    /**
     * @return string[]
     */
    private function getSalesChannelIds(Context $context): array
    {
        if (empty($this->defaultSalesChannelIds)) {
            /** @var string[] $ids */
            $ids                          = $this->salesChannelRepository->searchIds(new Criteria(), $context)->getIds();
            $this->defaultSalesChannelIds = $ids;
        }

        return $this->defaultSalesChannelIds;
    }

    private function getCountryId(string $id, Context $context): ?string
    {
        if ($this->isMatchingCountryId($id, $context)) {
            return $id;
        }

        /** @psalm-suppress InvalidArrayOffset */
        return $this->getCountryByIso(self::COUNTRY_ISOS[$id] ?? 'DE', $context);
    }

    /**
     * @psalm-param StoreRecord $data
     */
    private function createStoreId(array $data, Context $context): string
    {
        $id = $data['id'];

        if (isset($data['externalId']) && is_string($data['externalId'])) {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('externalId', $data['externalId']));

            $foundId = $this->repository->searchIds($criteria, $context)->firstId();

            if (null !== $foundId) {
                return $foundId;
            }
        }

        $criteria = new Criteria();

        foreach ($data as $key => $value) {
            if (!is_array($value) && !empty($value) && $key !== 'id') {
                $criteria->addFilter(new EqualsFilter($key, $value));
            }
        }

        $foundId = $this->repository->searchIds($criteria, $context)->firstId();

        return $foundId ?? $id;
    }

    private function setShowAlways(array $data): string
    {
        $newDisplay = 'no';

        if (!isset($data['showAlways'])) {
            return $newDisplay;
        }

        $showAlways = (string) $data['showAlways'];

        switch ($showAlways) {
            case '0':
                break;
            case '1':
                $newDisplay = 'top';
                break;
            case '2':
                $newDisplay = 'bottom';
                break;
        }

        return $newDisplay;
    }

    private function isMatchingSalesChannel(string $salesChannelId, Context $context): bool
    {
        if (isset($this->cachedSalesChannelIds[$salesChannelId])) {
            return true;
        }

        $criteria = new Criteria([ $salesChannelId ]);
        $result   = $this->salesChannelRepository->searchIds($criteria, $context)->firstId();

        if (is_string($result)) {
            $this->cachedSalesChannelIds[$salesChannelId] = $result;
        }

        return isset($this->cachedSalesChannelIds[$salesChannelId]);
    }

    /**
     * @psalm-param SalesChannelRecord[] $salesChannels
     *
     * @psalm-return SalesChannelRecord[]
     */
    private function assignSalesChannel(array $salesChannels, Context $context): array
    {
        foreach ($salesChannels as $key => $salesChannel) {
            if (!$this->isMatchingSalesChannel($salesChannel['id'], $context)) {
                unset($salesChannels[$key]);
            }
        }

        if (empty($salesChannels)) {
            $defaultSalesChannelIds = $this->getSalesChannelIds($context);
            foreach ($defaultSalesChannelIds as $defaultSalesChannelId) {
                $salesChannels[] = [ 'id' => $defaultSalesChannelId ];
            }
        }

        return $salesChannels;
    }

    /**
     * @psalm-param StoreRecord $data
     */
    private function assignCountryIdByIso(array $data, Context $context): ?string
    {
        if (
            isset($data['countryId'], $data['country']['iso'])
            && !$this->isMatchingCountryId(
                $data['countryId'],
                $context
            )
        ) {
            $data['countryId'] = $this->getCountryByIso($data['country']['iso'], $context);
        }

        if (!isset($data['countryId'])) {
            $data['countryId'] = $this->getCountryId('deutsch', $context);
        }

        return $data['countryId'] ?? null;
    }

    /**
     * @psalm-param StoreRecord $data
     */
    private function assignCountryStateByShortCode(array $data, Context $context): ?string
    {
        if (
            isset($data['countryStateId'], $data['countryState']['shortCode'])
            && !$this->isMatchingCountryStateId(
                $data['countryStateId'],
                $context
            )
        ) {
            $data['countryStateId'] = $this->getCountryStateByShortCode($data['countryState']['shortCode'], $context);
        }

        return $data['countryStateId'] ?? null;
    }

    private function isMatchingCountryId(string $countryId, Context $context): bool
    {
        if (isset($this->cachedCountryIds[$countryId])) {
            return true;
        }

        $criteria = new Criteria([ $countryId ]);
        $result   = $this->countryRepository->searchIds($criteria, $context)->firstId();

        if (is_string($result)) {
            $this->cachedCountryIds[$result] = $result;
        }

        return isset($this->cachedCountryIds[$countryId]);
    }

    private function getCountryByIso(string $iso, Context $context): ?string
    {
        if (isset($this->cachedIsoCountryIds[$iso])) {
            return $this->cachedIsoCountryIds[$iso];
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('iso', $iso));

        $countryId = $this->countryRepository->searchIds($criteria, $context)->firstId();

        if (is_string($countryId)) {
            $this->cachedIsoCountryIds[$iso] = $countryId;
        }

        return $countryId;
    }

    private function isMatchingCountryStateId(string $countryStateId, Context $context): bool
    {
        $criteria = new Criteria([ $countryStateId ]);
        $result   = $this->countryStateRepository->searchIds($criteria, $context)->getIds();

        return !empty($result);
    }

    private function getCountryStateByShortCode(string $shortCode, Context $context): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('shortCode', $shortCode));

        return $this->countryStateRepository->searchIds($criteria, $context)->firstId();
    }
}
