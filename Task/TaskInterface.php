<?php

namespace Jerive\Bundle\WorkflowBundle\Task;

/**
 * Description of TaskInterface
 *
 * @author jerome
 */
interface TaskInterface
{
    public function fire(CaseInterface $case);
}
