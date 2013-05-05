<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Workflow
 *
 * @author jerome
 */
class Node
{
    /**
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var Workflow
     * @Assert\NotBlank()
     */
    protected $workflow;

    public function __construct($options)
    {
        foreach(get_object_vars($this) as $key => $val) {
            $this->$key = isset($options[$key]) ? $options[$key] : $val;
        }
    }

    public function setWorkflow(Workflow $workflow)
    {
        if (isset($this->workflow)) {
            throw new \Exception('Cannot reset workflow');
        }

        $this->workflow = $workflow;
        return $this;
    }

    /**
     * @return Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    public function getOutputSet()
    {
        return $this->workflow->getOutputSet($this);
    }

    public function getInputSet()
    {
        return $this->workflow->getInputSet($this);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
