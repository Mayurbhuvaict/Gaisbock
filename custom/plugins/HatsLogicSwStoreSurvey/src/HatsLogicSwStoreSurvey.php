<?php

declare(strict_types=1);

namespace HatsLogic\HatsLogicSwStoreSurvey;

use Shopware\Core\Framework\Plugin;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use HatsLogic\HatsLogicSwStoreSurvey\Setup\Uninstaller;

class HatsLogicSwStoreSurvey extends Plugin
{
     /**
     * @param UninstallContext $context
     */

    public function uninstall(UninstallContext $context): void
    {
        $unInstaller = new Uninstaller(
            $this->container->get(Connection::class)
        );

        $unInstaller->uninstall($context);
    }
}
