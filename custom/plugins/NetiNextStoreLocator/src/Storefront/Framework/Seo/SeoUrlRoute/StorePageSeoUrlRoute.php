<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Storefront\Framework\Seo\SeoUrlRoute;

use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreDefinition;
use NetInventors\NetiNextStoreLocator\Core\Content\Store\StoreEntity;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlMapping;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteConfig;
use Shopware\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class StorePageSeoUrlRoute implements SeoUrlRouteInterface
{
    final public const ROUTE_NAME       = 'frontend.store_locator.detail';

    final public const DEFAULT_TEMPLATE = '{% if neti_store_locator.translated.seoUrl %}{{ neti_store_locator.translated.seoUrl }}{% else %}{{ neti_store_locator.label }}-{{ neti_store_locator.city }}{% endif %}';

    public function __construct(
        private readonly StoreDefinition $storeDefinition
    ) {
    }

    public function getConfig(): SeoUrlRouteConfig
    {
        return new SeoUrlRouteConfig(
            $this->storeDefinition,
            self::ROUTE_NAME,
            self::DEFAULT_TEMPLATE
        );
    }

    public function prepareCriteria(Criteria $criteria, SalesChannelEntity $salesChannel): void
    {
    }

    public function getMapping(Entity $entity, ?SalesChannelEntity $salesChannel): SeoUrlMapping
    {
        if (!$entity instanceof StoreEntity) {
            throw new \InvalidArgumentException('Expected StoreEntity');
        }

        $storeJson = $entity->jsonSerialize();

        return new SeoUrlMapping(
            $entity,
            [
                'id' => $entity->getId(),
            ],
            [
                'neti_store_locator' => $storeJson,
            ]
        );
    }
}
