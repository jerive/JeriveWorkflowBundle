<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Condition as BaseCondition;

class Condition extends BaseCondition
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
