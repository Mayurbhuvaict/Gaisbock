<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Core\Content\Seo;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Seo\AbstractSeoResolver;
use Shopware\Core\Framework\Uuid\Uuid;

class SeoResolverDecorator extends AbstractSeoResolver
{
    public function __construct(
        private readonly AbstractSeoResolver $mainService,
        private readonly Connection $connection
    ) {
    }

    public function resolve(string $languageId, string $salesChannelId, string $pathInfo): array
    {
        $seoPathInfo = ltrim($pathInfo, '/');

        if ($seoPathInfo === '') {
            return [ 'pathInfo' => '/', 'isCanonical' => false ];
        }

        $seoUrl = $this->getSeoUrl($salesChannelId);

        if (null !== $seoUrl) {
            $seoUrl = strtolower($seoUrl);

            if ($seoUrl === strtolower($seoPathInfo) || $seoUrl === strtolower(urldecode($seoPathInfo))) {
                return [
                    'pathInfo'    => '/StoreLocator',
                    'isCanonical' => true,
                ];
            }
        }

        return $this->mainService->resolve($languageId, $salesChannelId, $pathInfo);
    }

    private function getSeoUrl(string $salesChannelId): ?string
    {
        $query = $this->connection->createQueryBuilder()
            ->select('configuration_value')
            ->from('system_config')
            ->where('configuration_key = :configurationKey')
            ->andWhere('(sales_channel_id = :salesChannelId OR sales_channel_id IS NULL)')
            ->addOrderBy('sales_channel_id IS NULL')
            ->setMaxResults(1)
            ->setParameter('configurationKey', 'NetiNextStoreLocator.config.seoUrl')
            ->setParameter('salesChannelId', Uuid::fromHexToBytes($salesChannelId));

        /** @var string|false $json */
        $json = $query->fetchOne();

        if (is_string($json)) {
            /** @var array{_value: string} $data */
            $data = (array) json_decode($json, true);

            if (isset($data['_value'])) {
                return $data['_value'];
            }
        }

        return null;
    }

    public function getDecorated(): AbstractSeoResolver
    {
        return $this->mainService;
    }
}
