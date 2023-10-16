<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Service;

use NetInventors\NetiNextStoreLocator\Core\Content\BusinessHour\BusinessHourEntity;
use NetInventors\NetiNextStoreLocator\Core\Content\StoreBusinessHour\StoreBusinessHourEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;

class StoreBusinessHoursService
{
    public function __construct(
        private readonly EntityRepository $storeBusinessHoursRepository,
        private readonly EntityRepository $weekDayRepository
    ) {
    }

    public function getStoreBusinessHours(string $storeId, Context $context): array
    {
        $sortedBusinessHours = [];
        $criteria            = new Criteria();

        $criteria->addFilter(new EqualsFilter('storeId', $storeId));
        $criteria->addFilter(new MultiFilter(MultiFilter::CONNECTION_OR,
            [
                new EqualsFilter('specialDate', null),
                new RangeFilter('specialDate',
                    [
                        RangeFilter::GT => date(Defaults::STORAGE_DATE_FORMAT),
                    ]
                ),
            ]
        ));

        $criteria->addAssociation('businessHour');
        $criteria->addSorting(new FieldSorting('businessHour.start', FieldSorting::ASCENDING));

        $storeBusinessHours = $this->storeBusinessHoursRepository->search($criteria, $context);

        /** @var StoreBusinessHourEntity $storeBusinessHour */
        foreach ($storeBusinessHours->getElements() as $storeBusinessHour) {
            $businessWeekdayId = $storeBusinessHour->getBusinessWeekdayId();

            if (null !== $businessWeekdayId) {
                $sortedBusinessHours[$businessWeekdayId][] = $storeBusinessHour;
                continue;
            }

            if ($storeBusinessHour->isActive()) {
                $sortedBusinessHours['specialOpenDays'][] = $storeBusinessHour;
                continue;
            }

            $sortedBusinessHours['specialClosedDays'][] = $storeBusinessHour;
        }

        if (isset($sortedBusinessHours['specialOpenDays'])) {
            /** @psalm-suppress InvalidScalarArgument */
            usort($sortedBusinessHours['specialOpenDays'], static fn (StoreBusinessHourEntity $a, StoreBusinessHourEntity $b) => $a->getSpecialDate() > $b->getSpecialDate());
        }

        if (isset($sortedBusinessHours['specialClosedDays'])) {
            /** @psalm-suppress InvalidScalarArgument */
            usort($sortedBusinessHours['specialClosedDays'], static fn (StoreBusinessHourEntity $a, StoreBusinessHourEntity $b) => $a->getSpecialDate() > $b->getSpecialDate());
        }

        return $sortedBusinessHours;
    }

    public function getBusinessWeekdays(Context $context): array
    {
        $criteria = new Criteria();

        $criteria->addSorting(new FieldSorting('number', FieldSorting::ASCENDING));

        return $this->weekDayRepository->search($criteria, $context)->getElements();
    }

    public function isStoreOpen(\DateTime $dateTime, string $storeId, Context $context, bool $validateTime = true): bool
    {
        $dayNumber = $dateTime->format('N');
        $time      = $dateTime->format('H:i:s');
        $date      = $dateTime->format('Y-m-d');

        if ($this->isStoreExtraClosed($date, $time, $storeId, $context)) {
            return false;
        }

        $criteria = new Criteria();

        $criteria->addAssociations([ 'businessHour', 'businessWeekday' ]);
        $criteria->addFilter(new EqualsFilter('storeId', $storeId));

        $criteria->addFilter(
            new MultiFilter(
                MultiFilter::CONNECTION_OR,
                [
                    new EqualsFilter('businessWeekday.number', $dayNumber),
                    new MultiFilter(
                        MultiFilter::CONNECTION_AND,
                        [
                            new EqualsFilter('specialDate', $date),
                            new EqualsFilter('active', true),
                        ]
                    ),
                ]
            )
        );

        $storeBusinessHours = $this->storeBusinessHoursRepository->search($criteria, $context)->getElements();

        if ([] === $storeBusinessHours) {
            return false;
        }

        if (!$validateTime) {
            return true;
        }

        /** @var StoreBusinessHourEntity $storeBusinessHour */
        foreach ($storeBusinessHours as $storeBusinessHour) {
            if ($this->isOpenByTime($storeBusinessHour->getBusinessHour(), $time)) {
                return true;
            }
        }

        return false;
    }

    private function isOpenByTime(?BusinessHourEntity $businessHour, string $time): bool
    {
        if (null === $businessHour) {
            return false;
        }

        $storeStart = strtotime($businessHour->getStart());
        $storeEnd   = strtotime($businessHour->getEnd());

        return (strtotime($time) >= $storeStart && strtotime($time) <= $storeEnd);
    }

    private function isStoreExtraClosed(string $date, string $time, string $storeId, Context $context): bool
    {
        $criteria = new Criteria();

        $criteria->addAssociation('businessHour');
        $criteria->addFilter(new EqualsFilter('storeId', $storeId));

        $criteria->addFilter(
            new MultiFilter(
                MultiFilter::CONNECTION_AND,
                [
                    new EqualsFilter('specialDate', $date),
                    new EqualsFilter('active', false),
                ]
            ),
        );

        $storeBusinessHours = $this->storeBusinessHoursRepository->search($criteria, $context)->getElements();

        if ([] === $storeBusinessHours) {
            return false;
        }

        /** @var StoreBusinessHourEntity $storeBusinessHour */
        foreach ($storeBusinessHours as $storeBusinessHour) {
            if ($this->isOpenByTime($storeBusinessHour->getBusinessHour(), $time)) {
                return true;
            }
        }

        return false;
    }
}
