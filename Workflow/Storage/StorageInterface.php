<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Storage;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Task\TaskInterface;
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
     * @param Task $task
     * @param Caseinterface $case
     */
    public function isEnabled(Petri\Task $task, Caseinterface $case);

    /**
     *
     *
     * @param Workflow $workflow
     * @param CaseInterface $case
     */
    public function getAllEnabledTasks(Petri\Workflow $workflow, CaseInterface $case);

    /**
     *
     * @param Task $task
     * @param CaseInterface $case
     */
    public function executeActivity(Petri\Task $task, CaseInterface $case);
}
