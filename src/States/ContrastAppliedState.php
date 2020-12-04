<?php

namespace MriStateMachine\States;

class ContrastAppliedState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
    }

	public function returnToMriMachine()
	{
        $this->context->actions_history[] = 'restarted machine';
		$this->context->setState(new InMachineState());
	}
}
