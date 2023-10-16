<?php

declare(strict_types=1);
/**
 * (c) 2Hats Logic Solutions <info@2hatslogic.com>
 */

namespace HatsLogic\HatsLogicSwStoreSurvey\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class Uninstaller
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * Uninstaller constructor.
     *
     * @param Connection                $connection
     */
    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    /**
     * @param UninstallContext $uninstallContext
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->removeStoreSurveyTables();

        return;
    }

    public function removeStoreSurveyTables()
    {
        $this->connection->executeUpdate('DROP TABLE IF EXISTS `s_plugin_hatslogic_shopping_experiences`');
        return;
    }
}
