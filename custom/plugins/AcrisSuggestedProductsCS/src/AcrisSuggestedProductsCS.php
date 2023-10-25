<?php declare(strict_types=1);

namespace Acris\SuggestedProducts;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class AcrisSuggestedProductsCS extends Plugin
{
    public function getTemplatePriority(): int
    {
        return 120;
    }

    public function uninstall(UninstallContext $context): void
    {
        if ($context->keepUserData()) {
            return;
        }
        $this->cleanupDatabase();
    }

    private function cleanupDatabase(): void
    {
        $connection = $this->container->get(Connection::class);

        $connection->executeStatement('DROP TABLE IF EXISTS acris_customers_also_viewed');
    }
}
