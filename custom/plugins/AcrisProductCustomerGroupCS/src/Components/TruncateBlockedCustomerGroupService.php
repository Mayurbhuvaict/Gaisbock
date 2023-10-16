<?php declare(strict_types=1);

namespace Acris\ProductCustomerGroup\Components;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TruncateBlockedCustomerGroupService
{
    private ContainerInterface $container;

    public function __construct
    (
     ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    public function allowTruncate(): bool
    {
        $systemConfigService = $this->container->get(SystemConfigService::class);
        if (empty($systemConfigService)) return false;

        return $systemConfigService->get('AcrisProductCustomerGroupCS.config.truncateProductCustomerGroup') === true;
    }

    public function truncateBlockedCustomerGroups(string $productId): void
    {
        try {
            $connection = $this->container->get(Connection::class);

            $productId = Uuid::fromHexToBytes($productId);

            $connection->executeStatement('DELETE FROM `acris_product_customer_group` WHERE product_id = ?;', [$productId]);
        } catch (\Throwable $e) {}
    }
}
