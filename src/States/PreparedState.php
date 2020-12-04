<?php

namespace MriStateMachine\States;

class PreparedState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'get instructions from technician';
    }

	public function getInMriMachine()
	{
        $this->context->actions_history[] = 'put blanked, earplugs, and mentally prepare for being a statue for 30 mins';
		$this->context->setState(new InMachineState());
	}
}
