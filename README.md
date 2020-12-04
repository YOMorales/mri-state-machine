#### Overview

This is an exercise in making a simple state machine in PHP. It is modeled after
the processes that a patient undergoes when taking an MRI (Magnetic Resonance Imaging)
test. An MRI test usually involves several steps that the patient must take in order.

https://en.wikipedia.org/wiki/Magnetic_resonance_imaging

This exercise includes a Patient class that acts as the 'context' (or stateful object)
to which states will be applied. Then there are several State classes. Each of those
State classes defines only the transitions that they are allowed to do. For other
transitions not allowed, an exception is thrown.

Then each State also has miscellaneous events and actions (actually, mocked with dummy
data) that are called when the state is applied.

#### Installation and Usage

Simply do a `composer install` and then run the Patient test with
`./vendor/bin/phpunit tests/PatientTest.php`. Looking at the PatientTest class, you
will see how the states are applied and used.
