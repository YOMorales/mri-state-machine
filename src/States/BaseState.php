<?php

namespace MriStateMachine\States;

use MriStateMachine\Traits\BaseStateTrait;

abstract class BaseState implements StateInterface
{
	public $context;

    abstract public function initialAction(array $initial_params = null);
}
