<?php declare(strict_types=1);

namespace Swag\Security\Tests\Helper;

use Swag\Security\Components\State;

trait StateHelper
{
    private $previousState;

    protected function setStateConfig(array $config): void
    {
        $state = $this->getContainer()->get(State::class);

        $f = \Closure::bind(function (State $state) use ($config): void {
            $state->activeFixes = $config;
            $state->availableFixes = $config;
        }, null, State::class);

        $f($state);
    }

    /**
     * @after
     */
    protected function resetStateConfig(): void
    {
        $this->setStateConfig($this->getContainer()->getParameter('SwagPlatformSecurity.activeFixes'));
    }
}
