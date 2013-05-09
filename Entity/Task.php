<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Task as BaseTask;

use Doctrine\Common\Collections\ArrayCollection;

class Task extends BaseTask
{
    protected $id;

    protected $name;

    protected $outputSet;

    protected $inputSet;

    public function __construct()
    {
        $this->outputSet = new ArrayCollection;
        $this->inputSet  = new ArrayCollection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
