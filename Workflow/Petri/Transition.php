<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Transition
 *
 * @author jerome
 */
class Transition extends Node
{
    /**
     * @Assert\Type(type="string")
     */
    protected $class;

    /**
     * @Assert\Type(type="string")
     */
    protected $serviceId;

    /**
     * @var string
     * @Assert\NotBlank')
     */
    protected $type;

    public function getType()
    {
        return $this->type;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function getClass()
    {
        return $this->class;
    }
}
