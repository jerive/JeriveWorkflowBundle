<?php

namespace Jerive\Bundle\WorkflowBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

use Jerive\Bundle\WorkflowBundle\Transition\TransitionInterface;

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
    public $join    = TransitionInterface::SPLIT_AND;
    /** @var string */
    public $split   = TransitionInterface::SPLIT_AND;
    /** @var string */
    public $trigger = TransitionInterface::TRIGGER_AUTO;
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
