<?php

namespace Jerive\Bundle\WorkflowBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri\Transition;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Transition extends Annotation
{
    /** @var string */
    public $workflow = 'default';
    /** @var string */
    public $serviceId;
    /** @var string */
    public $join    = Transition::SPLIT_AND;
    /** @var string */
    public $split   = Transition::SPLIT_AND;
    /** @var string */
    public $trigger = Transition::TRIGGER_AUTO;
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Place> */
    public $input = array();
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Place> */
    public $output = array();

    public function getServiceId()
    {
        if ($this->serviceId) {
            return $this->serviceId;
        }

        return $this->value;
    }
}
