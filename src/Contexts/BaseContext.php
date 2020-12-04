<?php

namespace MriStateMachine\Contexts;

use MriStateMachine\Exceptions\IllegalStateTransitionException;
use MriStateMachine\States\StateInterface;

/**
 * Abstract class from which other 'Context' classes will inherit.
 *
 * 'Context' here refers to the stateful object.
 */
abstract class BaseContext
{
    /**
     * Holds the current state (object) of the Patient context.
     *
     * @var object
     */
    protected $state;

    /**
     * Keeps a list of operations that the context does.
     *
     * This is mostly to mock operations (other than state changes) done
     * on or by the context. For example, in one particular state, we would
     * mock the sending of an email or the filling of a form. We would log that
     * operation in this array, for later inspection in test cases.
     *
     * This was done for illutrative purposes in this exercise. A real app
     * wouldn't require this.
     *
     * @var array
     */
    public $actions_history = [];

    /**
     * Keeps a list of the states that were applied to a Patient object.
     *
     * Mostly for testing/mocking in this exercise.
     *
     * @var array
     */
    public $states_history = [];

    /**
     * Getter for self::$state.
     *
     * @return object
     */
    public function getState(): StateInterface
    {
        return $this->state;
    }

    /**
     * Setter for self::$state.
     *
     * @param object $state
     * @return object
     */
    public function setState(StateInterface $state, array $initial_params = null): StateInterface
    {
        $this->state = $state;

        // 'logs' the state that was set (in a real exercise, this would be logged to a db)
        $this->states_history[] = get_class($state);

        // passes the context object to the state in case it wants to manipulate it
        $this->state->context = $this;

        $this->state->initialAction($initial_params);

        return $this->state;
    }

    /**
     * Checks if self::$state is holding a specific class.
     *
     * @param string $class_name The name of the class that self::$state should be.
     * @return bool
     */
    public function isState(string $class_name): bool
    {
        return $this->state instanceof $class_name;
    }

    /**
     * Makes the transition to another state.
     *
     * This will call a method within the current state, which will
     * apply another state (allowed by the current state) to the patient
     * object and will also execute any other events defined within
     * the applied state class.
     *
     * @param string $transition_name
     * @return BaseContext
     */
    public function transition(string $transition_name): BaseContext
    {
        if (! method_exists($this->state, $transition_name)) {
            $error = sprintf(
                "Transition '%s' called on state '%s'.",
                $transition_name,
                substr(strrchr(get_class($this->state), '\\'), 1)
            );

            throw new IllegalStateTransitionException($error);
        }

        $this->state->{$transition_name}();

        // to make this method chainable
        return $this;
    }

    /**
     * Checks if a transition is allowed on the current state.
     *
     * Checks by way of inspecting if the transition name was
     * defined in the current state class.
     *
     * @param string $transition_name
     * @return boolean
     */
    public function can(string $transition_name): bool
    {
        return method_exists($this->state, $transition_name);
    }
}
