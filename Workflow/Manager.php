<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow;

use Doctrine\Common\Cache\Cache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Workflow\Storage\StorageInterface;
use Jerive\Bundle\WorkflowBundle\Entity\CaseInterface;

/**
 * Description of Manager
 *
 * @author jerome
 */
class Manager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var StorageInterface
     */
    protected $storage;

    protected $cache;

    public function __construct(ContainerInterface $container, EventDispatcherInterface $dispatcher, StorageInterface $storage)
    {
        $this->storage    = $storage;
        $this->container  = $container;
        $this->dispatcher = $dispatcher;
    }

    public function registerWorkflow(Petri\Workflow $workflow)
    {
        $this->workflows[$workflow->getName()] = $workflow;
    }

    /**
     *
     * @param string $name
     * @return Petri\Workflow
     */
    public function getWorkflow($name)
    {
        return $this->workflows[$name];
    }

    /**
     * Execution of an activity
     * ACID advance in the workflow
     *
     * @param Transition $transition
     * @param CaseInterface $case
     * @throws \Exception
     */
    public function executeActivity(Petri\Transition $transition, CaseInterface $case)
    {
        if ($this->storage->isEnabled($transition, $case)) {
            $this->storage->executeActivity($transition, $case, $this->container->get($transition->getServiceId()));
        } else {
            throw new \LogicException('Tried to trigger an unabled transaction');
        }

        return $this;
    }

    public function getEnabledTransitions(Petri\Workflow $workflow, CaseInterface $case)
    {
        return $this->storage->getEnabledTransitions($place);
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}
