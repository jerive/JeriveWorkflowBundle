<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Transition;

/**
 *
 */
interface TimeoutTransitionInterface extends TransitionInterface
{
    public function getTimeout();
}
