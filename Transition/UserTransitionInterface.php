<?php

namespace Jerive\Bundle\WorkflowBundle\Transition;

/**
 *
 */
interface UserTransitionInterface extends TransitionInterface
{
    public function ask();

    public function validate();
}
