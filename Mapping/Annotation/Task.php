<?php

namespace Jerive\Bundle\WorkflowBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri\Task;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Task extends Annotation
{
    /** @var string */
    public $workflow = 'default';
    /** @var string */
    public $serviceId;
    /** @var string */
    public $join    = Task::SPLIT_AND;
    /** @var string */
    public $split   = Task::SPLIT_AND;
    /** @var string */
    public $trigger = Task::TRIGGER_AUTO;
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Condition> */
    public $input = array();
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Condition> */
    public $output = array();

    public function getServiceId()
    {
        if ($this->serviceId) {
            return $this->serviceId;
        }

        return $this->value;
    }
}
