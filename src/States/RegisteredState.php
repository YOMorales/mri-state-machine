<?php

namespace MriStateMachine\States;

class RegisteredState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
    }

	public function prepare($mount_valve = true)
	{
        $this->context->actions_history[] = 'remove all metal objects';

        if ($mount_valve) {
            $this->context->actions_history[] = 'put intravenous valve on patient';
        }

		$this->context->setState(new PreparedState());
	}
}
