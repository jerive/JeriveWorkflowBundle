<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Storage;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Transition\TransitionInterface;
use Jerive\Bundle\WorkflowBundle\Entity\CaseInterface;

/**
 * Description of StorageInterface
 *
 * @author jerome
 */
interface StorageInterface
{
    /**
     *
     * @param Transition $transition
     * @param Caseinterface $case
     */
    public function isEnabled(Petri\Transition $transition, Caseinterface $case);

    /**
     *
     *
     * @param Workflow $workflow
     * @param CaseInterface $case
     */
    public function getAllEnabledTransitions(Petri\Workflow $workflow, CaseInterface $case);

    /**
     *
     * @param Transition $transition
     * @param CaseInterface $case
     */
    public function executeActivity(Petri\Transition $transition, CaseInterface $case);
}
