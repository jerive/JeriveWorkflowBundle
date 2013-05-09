<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Workflow as BaseWorkflow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jerive_workflow")
 */
class Workflow extends BaseWorkflow
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Task")
     */
    protected $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Condition")
     */
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
}

