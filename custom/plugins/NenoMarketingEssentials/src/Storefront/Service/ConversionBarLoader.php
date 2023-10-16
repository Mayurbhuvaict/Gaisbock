<?php declare(strict_types=1);

namespace Neno\MarketingEssentials\Storefront\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Neno\MarketingEssentials\Core\Content\ConversionBar\ConversionBarEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class ConversionBarLoader {
    public function __construct(private readonly EntityRepository $conversionBarRepository)
    {
    }

    private function fetchDefaultConfig(Context $context): ConversionBarEntity {
        // default config matches all sales channels
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('salesChannelId',null));

        $defaultBarResult = $this->conversionBarRepository
            ->search($criteria, $context)
            ->getEntities();

        if ($defaultBarResult->count()) {
            return $defaultBarResult->first();
        } else {
            return new ConversionBarEntity();
        }
    }

    public function loadForSalesChannel(Context $context, SalesChannelContext $salesChannelContext): EntityCollection {
        $salesChannelId = $salesChannelContext->getSalesChannelId();

        $defaultBarResult = $this->fetchDefaultConfig($context);

        // per sales channel config
        $criteria = (new Criteria())
            ->addFilter(
                new EqualsFilter(
                    'salesChannelId',
                    $salesChannelId
                ),
            );

        /** @var ConversionBarEntity|null $barResult */
        $barResult = $this->conversionBarRepository
            ->search($criteria, $context)
            ->getEntities()
            ->first();

        if ($barResult) {
            // merge with defaults
            if ($barResult->getBackgroundColor() === null) {
                $barResult->setBackgroundColor($defaultBarResult->getBackgroundColor());
            }

            if ($barResult->getSliderMaxWidth() === null) {
                $barResult->setSliderMaxWidth($defaultBarResult->getSliderMaxWidth());
            }

            if ($barResult->getTextColor() === null) {
                $barResult->setTextColor($defaultBarResult->getTextColor());
            }

            if ($barResult->getLinkColor() === null) {
                $barResult->setLinkColor($defaultBarResult->getLinkColor());
            }

            return new EntityCollection([$barResult]);
        } else {
            return new EntityCollection([$defaultBarResult]);
        }
    }
}
