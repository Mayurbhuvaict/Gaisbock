<?php

declare(strict_types=1);

namespace NetInventors\NetiNextStoreLocator;

use Doctrine\DBAL\Connection;
use NetInventors\NetiNextStoreLocator\Components\Setup;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NetiNextStoreLocator extends Plugin
{
    /**
     * @param InstallContext $installContext
     *
     * @throws \Exception
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        if (!$this->container instanceof ContainerInterface) {
            return;
        }

        $setup = new Setup($this->container, $installContext);

        $setup->install($installContext->getContext());
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if (false === $uninstallContext->keepUserData()) {
            if (!$this->container instanceof ContainerInterface) {
                return;
            }

            $setup = new Setup($this->container, $uninstallContext);

            $setup->uninstall($uninstallContext->getContext());

            $connection = $this->container->get(Connection::class);

            if (!$connection instanceof Connection) {
                return;
            }

            $query = <<<SQL
SET foreign_key_checks=0;
DROP TABLE IF EXISTS `neti_store_locator`,
`neti_store_locator_contact_form`,
`neti_store_locator_contact_form_translation`,
`neti_store_locator_translation`,
`neti_store_sales_channel`,
`neti_store_tag`,
`neti_business_weekday`,
`neti_business_weekday_translation`,
`neti_business_hour`,
`neti_business_hour_translation`,
`neti_store_cms`,
`neti_store_business_hour`;
SET foreign_key_checks=1;
SQL;
            $connection->executeQuery($query);

            $query = <<<SQL
DELETE FROM `media_folder` WHERE `default_folder_id` = (SELECT `id` FROM `media_default_folder` WHERE `media_default_folder`.`entity` = 'neti_store_locator');
SQL;
            $connection->executeStatement($query);

            $query = <<<SQL
DELETE FROM `media_default_folder` WHERE `entity` = 'neti_store_locator';
SQL;
            $connection->executeStatement($query);

            $sql = '
                DELETE FROM seo_url_template WHERE entity_name = "neti_store_locator"
            ';

            $connection->executeStatement($sql);
        }

        // Since it is not possible to remove the profile if an import/export was done, the uninstall method is deactivated until it is possible
        // Setup::uninstallImportExportProfile($this->container, $uninstallContext->getContext());
    }

    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);

        if (!$this->container instanceof ContainerInterface) {
            return;
        }

        $setup = new Setup($this->container, $updateContext);

        $setup->update($updateContext->getContext());
    }
}
