<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Condition as BaseCondition;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Condition extends BaseCondition
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
     * @ORM\ManyToOne(targetEntity="Workflow")
     */
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
}

