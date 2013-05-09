<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Workflow as BaseWorkflow;

class Workflow extends BaseWorkflow
{
    protected $id;

    protected $tasks;

    protected $conditions;

    public function getId()
    {
        return $this->id;
    }
}
