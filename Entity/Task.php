<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Jerive\Workflow\Petri\Task as BaseTask;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Task extends BaseTask
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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $split;

    /**
     * @ORM\Column(type="string")
     */
    protected $join;

    /**
     * @ORM\Column(type="string")
     */
    protected $serviceId;

    /**
     * @ORM\ManyToOne(targetEntity="Workflow")
     */
    protected $workflow;

    /**
     * @ORM\ManyToMany(targetEntity="Condition")
     */
    protected $outputSet;

    /**
     * @ORM\ManyToMany(targetEntity="Condition")
     */
    protected $inputSet;

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
