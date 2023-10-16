<?php
declare(strict_types=1);

namespace Laenen\Giftcard;

use Doctrine\DBAL\Connection;
use Laenen\Giftcard\Migration\Migration1668171210AddGiftcardMailTemplate;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Uuid\Uuid;

class LaenenGiftcard extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        if (!$connection instanceof Connection) {
            return;
        }

        $connection->executeStatement(<<<SQL
DROP TABLE IF EXISTS `lae_giftcard`, `lae_giftcard_transaction`;

DELETE FROM mail_template_type WHERE technical_name = 'lae_giftcard_created';
DELETE FROM mail_template WHERE id = :mailTemplateId
SQL, [
            'mailTemplateId' => Uuid::fromHexToBytes(Migration1668171210AddGiftcardMailTemplate::MAIL_TEMPLATE_ID),
        ]);
    }
}
