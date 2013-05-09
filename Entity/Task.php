<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Task
{
    protected $id;

    protected $name;

    protected $title;

    protected $type;

    protected $split;

    protected $join;

    protected $serviceId;

    protected $workflow;

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
