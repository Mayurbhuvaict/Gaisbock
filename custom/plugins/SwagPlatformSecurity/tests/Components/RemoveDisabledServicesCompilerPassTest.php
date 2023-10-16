<?php declare(strict_types=1);

namespace Swag\Security\Tests\Components;

use PHPUnit\Framework\TestCase;
use Swag\Security\Components\RemoveDisabledServicesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 */
class RemoveDisabledServicesCompilerPassTest extends TestCase
{
    public function testEnabledFixStaysInContainer(): void
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new RemoveDisabledServicesCompilerPass());
        $container->setDefinition('test', (new Definition(self::class))->addTag('swag.security.fix', ['ticket' => 'test'])->setPublic(true));
        $container->setParameter('SwagPlatformSecurity.activeFixes', ['test']);
        $container->compile();

        static::assertTrue($container->hasDefinition('test'));
    }

    public function testDisabledFixGetServicesRemoved(): void
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new RemoveDisabledServicesCompilerPass());
        $container->setDefinition('test', (new Definition(self::class))->addTag('swag.security.fix', ['ticket' => 'test'])->setPublic(true));
        $container->setParameter('SwagPlatformSecurity.activeFixes', []);
        $container->compile();

        static::assertFalse($container->hasDefinition('test'));
    }
}
