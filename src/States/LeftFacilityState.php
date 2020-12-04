<?php

namespace MriStateMachine\States;

class LeftFacilityState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'stretch legs and go home';
    }
}
