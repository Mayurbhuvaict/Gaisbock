<?php declare(strict_types=1);

/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Connection;
use Shopware\Core\DevOps\StaticAnalyze\StaticAnalyzeKernel;
use Shopware\Core\Framework\Plugin\KernelPluginLoader\DbalKernelPluginLoader;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Symfony\Component\Dotenv\Dotenv;

function getProjectDir(): string
{
    if (isset($_SERVER['PROJECT_ROOT']) && \file_exists($_SERVER['PROJECT_ROOT'])) {
        return $_SERVER['PROJECT_ROOT'];
    }
    if (isset($_ENV['PROJECT_ROOT']) && \file_exists($_ENV['PROJECT_ROOT'])) {
        return $_ENV['PROJECT_ROOT'];
    }

    $rootDir = __DIR__;
    $dir = $rootDir;
    while (!\file_exists($dir . '/vendor')) {
        if ($dir === \dirname($dir)) {
            return $rootDir;
        }
        $dir = \dirname($dir);
    }

    return $dir;
}

require __DIR__ . '/KernelLifecycleManager.php';

$testProjectDir = getProjectDir();

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require $testProjectDir . '/vendor/autoload.php';

foreach (['Shopware\Development\Kernel', 'Shopware\Production\Kernel', 'Shopware\Core\Kernel'] as $kernelClass) {
    if (class_exists($kernelClass)) {
        $_SERVER['KERNEL_CLASS'] = $kernelClass;

        break;
    }
}

KernelLifecycleManager::prepare($loader);
$loader->addPsr4('Swag\\Security\\Tests\\', __DIR__);
$loader->addPsr4('Swag\\Security\\', dirname(__DIR__) . '/src');
$loader->addPsr4('Shopware\\', dirname(__DIR__) . '/core');

if (!\class_exists(Dotenv::class)) {
    throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
}

if (file_exists($testProjectDir . '/.env')) {
    if ((new ReflectionClass(Dotenv::class))->getConstructor()?->getParameters()[0]?->getName() === 'envKey') {
        (new Dotenv())->bootEnv($testProjectDir . '/.env');
    } else {
        (new Dotenv())->load($testProjectDir . '/.env');
    }
}

$dbUrl = \getenv('DATABASE_URL');
if ($dbUrl !== false) {
    \putenv('DATABASE_URL=' . $dbUrl . '_test');
}

// build StaticAnalyzeKernel container for phpstan
$testKernel = KernelLifecycleManager::getKernel();
$pluginLoader = new DbalKernelPluginLoader($loader, null, $testKernel->getContainer()->get(Connection::class));
$kernel = new StaticAnalyzeKernel('test', true, $pluginLoader, 'phpstan-test-cache-id');
$kernel->boot();

return $loader;
