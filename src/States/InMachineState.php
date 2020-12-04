<?php

namespace MriStateMachine\States;

class InMachineState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'put music, and fire it up';
    }

	public function interruptMriCycle()
	{
		$this->context->setState(new InterruptedMriCycleState());
    }

	public function completeMriCycle()
	{
		$this->context->setState(new OutOfMachineState());
    }
}
