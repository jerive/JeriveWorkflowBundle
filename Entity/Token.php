<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

abstract class Token implements HasCaseInterface
{
    protected $id;

    protected $place;

    protected $created_date;

    public function getId()
    {
        return $this->id;
    }

    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function prePersist()
    {
        $this->created_date = new \DateTime('now');
    }
}
