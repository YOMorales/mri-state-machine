<?php

namespace MriStateMachine\States;

class OutOfMachineState extends BaseState
{
    public function initialAction(array $initial_params = null)
    {
        $this->context->actions_history[] = 'shut off machine';
    }

	public function putContrast()
	{
        if (! in_array('put intravenous valve on patient', $this->context->actions_history)) {
            $this->context->actions_history[] = 'put intravenous valve on patient';
        }

        $this->context->setState(new ContrastAppliedState());
    }

	public function leaveFacility()
	{
		$this->context->setState(new LeftFacilityState());
    }
}
