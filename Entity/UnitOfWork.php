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

    protected $transition;

    protected $created_date;

    protected $processed_date;

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
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
