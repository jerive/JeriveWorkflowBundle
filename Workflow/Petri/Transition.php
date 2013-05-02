<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Workflow
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
}
