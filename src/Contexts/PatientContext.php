<?php

namespace MriStateMachine\Contexts;

use MriStateMachine\States\StateInterface;

/**
 * Patient Context.
 *
 * 'Context' here refers to the stateful object.
 */
class PatientContext extends BaseContext
{
    public function __construct(StateInterface $state)
    {
        $this->setState($state);
    }
}
