<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

/**
 * Description of UnitOfWork
 *
 * @author jerome
 */
abstract class UnitOfWork implements HasCaseInterface
{
    protected $id;

    protected $hash;

    protected $workflow;

    protected $transition;

    protected $type;

    protected $user;

    protected $group;

    protected $created_date;

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getWorkflow()
    {
        return $this->workflow;
    }

    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }

    public function getTransition()
    {
        return $this->transition;
    }

    public function setTransition($transition)
    {
        $this->transition = $transition;
        return $this;
    }

    public function prePersist()
    {
        $this->hash = md5(uniqid());
        $this->created_date = new \DateTime('now');
    }
}
