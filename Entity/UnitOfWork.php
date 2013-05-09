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

    protected $task;

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

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    public function setTask(Task $task)
    {
        $this->task = $task;
        return $this;
    }

    public function prePersist()
    {
        $this->hash = md5(uniqid());
        $this->created_date = new \DateTime('now');
    }
}
