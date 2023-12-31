<?php declare(strict_types=1);

namespace Shopware\Core\System\StateMachine\Exception;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\System\StateMachine\StateMachineException;
use Symfony\Component\HttpFoundation\Response;

#[Package('checkout')]
class IllegalTransitionException extends StateMachineException
{
    /**
     * @param array<mixed> $possibleTransitions
     */
    public function __construct(
        string $currentState,
        string $transition,
        array $possibleTransitions
    ) {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::ILLEGAL_STATE_TRANSITION,
            'Illegal transition "{{ transition }}" from state "{{ currentState }}". Possible transitions are: {{ possibleTransitionsString }}',
            [
                'transition' => $transition,
                'currentState' => $currentState,
                'possibleTransitionsString' => implode(', ', $possibleTransitions),
                'possibleTransitions' => $possibleTransitions,
            ]
        );
    }
}
