<?php

namespace Jerive\Bundle\WorkflowBundle\Transition;

/**
 *
 */
interface TimeoutTransitionInterface extends TransitionInterface
{
    public function getTimeout();
}
