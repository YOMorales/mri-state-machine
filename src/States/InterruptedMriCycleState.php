<?php

namespace MriStateMachine\States;

class InterruptedMriCycleState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'call technician for help';
    }

	public function resumeMriCycle()
	{
		$this->context->setState(new InMachineState());
    }

	public function abortMriCycle()
	{
		$this->context->setState(new OutOfMachineState());
    }
}
