<?php declare(strict_types=1);

namespace Swkweb\NewsletterAtRegister;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

final class SwkwebNewsletterAtRegister extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->dropSchema();
    }

    private function dropSchema(): void
    {
        /** @var Connection */
        $connection = $this->container->get(Connection::class);

        $connection->executeStatement('DROP TABLE IF EXISTS swkweb_newsletter_at_register_subscription;');
    }
}
