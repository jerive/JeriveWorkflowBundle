<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

abstract class Token implements HasCaseInterface
{
    protected $id;

    protected $condition;

    protected $created_date;

    public function getId()
    {
        return $this->id;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function prePersist()
    {
        $this->created_date = new \DateTime('now');
    }
}
