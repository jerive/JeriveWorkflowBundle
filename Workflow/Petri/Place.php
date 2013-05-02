<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Workflow
 *
 * @author jerome
 */
class Place extends Node
{
    /**
     * @Assert\Type(type="bool")
     */
    protected $input = false;

    /**
     * @Assert\Type(type="bool")
     */
    protected $output = false;

    public function isInput()
    {
        return $this->input;
    }

    public function isOutput()
    {
        return $this->output;
    }
}
