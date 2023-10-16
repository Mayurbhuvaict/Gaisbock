<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Components\ContactForm;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class ContactForm
{
    public function __construct(
        private readonly EntityRepository $contactFormRepository
    ) {
    }

    public function getFields(SalesChannelContext $context): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addSorting(new FieldSorting('position'));

        $result = $this->contactFormRepository->search($criteria, $context->getContext());

        return $result->getElements();
    }
}
