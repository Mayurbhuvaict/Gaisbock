<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator\Migration;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Storefront\Framework\Seo\SeoUrlRoute\StorePageSeoUrlRoute;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1578066584CreateSeoTemplate extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_578_066_584;
    }

    public function update(Connection $connection): void
    {
        $id = $connection->executeQuery('SELECT id FROM seo_url_template WHERE route_name = "frontend.store_locator.detail"')->fetchAllAssociative();

        if (!empty($id)) {
            return;
        }

        $connection->insert(
            'seo_url_template',
            [
                'id'               => Uuid::randomBytes(),
                'sales_channel_id' => null,
                'route_name'       => StorePageSeoUrlRoute::ROUTE_NAME,
                'entity_name'      => 'neti_store_locator',
                'template'         => StorePageSeoUrlRoute::DEFAULT_TEMPLATE,
                'is_valid'         => true,
                'created_at'       => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
