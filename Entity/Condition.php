<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Condition as BaseCondition;
use Doctrine\ORM\Mapping as ORM;

class Condition
{
    protected $id;

    protected $name;

    protected $workflow;

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

    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }
}

