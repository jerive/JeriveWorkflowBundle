<?php

namespace Jerive\Bundle\WorkflowBundle\Transition;

/**
 * Description of TransitionInterface
 *
 * @author jerome
 */
interface TransitionInterface
{
    public function fire(CaseInterface $case);
}
