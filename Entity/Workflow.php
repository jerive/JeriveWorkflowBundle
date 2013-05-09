<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

class Workflow
{
    protected $id;

    protected $name;

    protected $title;

    protected $tasks;

    protected $conditions;

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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
