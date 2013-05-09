<?php

namespace Jerive\Bundle\WorkflowBundle\Task;

/**
 *
 */
interface TimeoutTaskInterface extends TaskInterface
{
    public function getTimeout();
}
