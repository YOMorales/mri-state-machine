<?php

namespace MriStateMachine\States;

class ArrivedState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'take papers';
        $this->context->actions_history[] = 'sit and wait';
    }

	public function register()
	{
        $this->context->actions_history[] = 'forms filled and saved to db';
		$this->context->setState(new RegisteredState());
	}
}
