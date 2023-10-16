<?php declare(strict_types=1);

namespace Swag\Security\Tests\Components;

use PHPUnit\Framework\TestCase;
use Swag\Security\Tests\State\TestFix;
use Swag\Security\Tests\State\TestState;

/**
 * @internal
 */
class StateTest extends TestCase
{
    public function testStateActive(): void
    {
        $state = new TestState([TestFix::class], [TestFix::class]);

        static::assertTrue($state->isActive('test'));
        static::assertSame([TestFix::class], $state->getActiveFixes());
        static::assertSame([TestFix::class], $state->getAvailableFixes());
    }

    public function testStateInactive(): void
    {
        $state = new TestState([TestFix::class], []);

        static::assertFalse($state->isActive('test'));
        static::assertSame([], $state->getActiveFixes());
        static::assertSame([TestFix::class], $state->getAvailableFixes());
    }
}
