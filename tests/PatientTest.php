<?php
declare(strict_types = 1);

namespace MriStateMachine\Tests;

use MriStateMachine\Contexts\PatientContext;
use MriStateMachine\Exceptions\IllegalStateTransitionException;
use MriStateMachine\States\ArrivedState;
use MriStateMachine\States\ContrastAppliedState;
use MriStateMachine\States\InMachineState;
use MriStateMachine\States\InterruptedMriCycleState;
use MriStateMachine\States\LeftFacilityState;
use MriStateMachine\States\OutOfMachineState;
use MriStateMachine\States\PreparedState;
use MriStateMachine\States\RegisteredState;
use PHPUnit\Framework\TestCase;

class PatientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function patient_context_is_initialized_with_one_state()
    {
        /**
         * In a real world app, this should be initialized from a state gotten from db
         * or supplied by the user, in which case the supplied state should be validated that
         * is not a state out of the correct order (either by comparing with all past state
         * records in db, or by inferring past states based on the current state
         * among a defined sequence of states).
         */
        $patient = new PatientContext(new ArrivedState());

        $this->assertTrue($patient->isState(ArrivedState::class));
        // these are dummy 'events' that were executed when the state was applied
        $this->assertEquals('take papers', $patient->actions_history[0]);
        $this->assertEquals('sit and wait', $patient->actions_history[1]);
    }

    /**
     * @test
     */
    public function patient_changes_states_several_times()
    {
        $patient = new PatientContext(new ArrivedState());

        // transition to register state
        $patient->transition('register');
        $this->assertTrue($patient->isState(RegisteredState::class));
        $this->assertEquals('forms filled and saved to db', $patient->actions_history[2]);

        $patient->transition('prepare');
        $this->assertTrue($patient->isState(PreparedState::class));
        $this->assertEquals('remove all metal objects', $patient->actions_history[3]);
    }

    /**
     * @test
     */
    public function checks_if_specific_transitions_exist_in_a_state()
    {
        $patient = new PatientContext(new PreparedState());

        $this->assertTrue($patient->can('getInMriMachine'));
        $this->assertFalse($patient->can('interruptMriCycle'));
    }

    /**
     * @test
     */
    public function exception_happens_if_transition_method_is_called_on_wrong_state()
    {
        $patient = new PatientContext(new ArrivedState());

        $this->expectException(IllegalStateTransitionException::class);
        $patient->transition('prepare');
    }

    /**
     * @test
     */
    public function intializing_patient_with_different_state()
    {
        $patient = new PatientContext(new PreparedState());

        $patient->transition('getInMriMachine');

        $this->assertTrue($patient->isState(InMachineState::class));
        $actions_done = [
            'get instructions from technician',
            'put blanked, earplugs, and mentally prepare for being a statue for 30 mins',
            'put music, and fire it up'
        ];
        $this->assertEquals($actions_done, $patient->actions_history);
    }

    /**
     * @test
     */
    public function patient_undergoes_complete_transition_set()
    {
        $patient = new PatientContext(new ArrivedState());

        $patient->transition('register');

        $patient->transition('prepare');

        $patient->transition('getInMriMachine');

        $patient->transition('interruptMriCycle');

        $patient->transition('resumeMriCycle');

        $patient->transition('completeMriCycle');

        $patient->transition('putContrast');

        $patient->transition('returnToMriMachine');

        // can also make this method chainable
        $patient
            ->transition('completeMriCycle')
            ->transition('leaveFacility');

        // now asserts that the patient went through all these states in this order
        $states_done = [
            ArrivedState::class,
            RegisteredState::class,
            PreparedState::class,
            InMachineState::class,
            InterruptedMriCycleState::class,
            InMachineState::class,
            OutOfMachineState::class,
            ContrastAppliedState::class,
            InMachineState::class,
            OutOfMachineState::class,
            LeftFacilityState::class,
        ];
        $this->assertEquals($states_done, $patient->states_history);

        /**
         * @todo In the future, test that the states done and their order
         * match a predefined set of states that should have been done. Make
         * sure to account for optional states and repeated states.
         */
    }
}
