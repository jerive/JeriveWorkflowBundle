<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Cache\Cache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Transition\TransitionInterface;
use Jerive\Bundle\WorkflowBundle\Entity\CaseInterface;

/**
 * Description of Manager
 *
 * @author jerome
 */
class Manager
{
    private $container;

    protected $doctrine;

    protected $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->doctrine  = $container->get('doctrine');
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
     * @param string $transition
     */
    public function processTransition(Petri\Transition $transition, CaseInterface $case)
    {
        $this->getManager()->beginTransaction();

        try {
            /** @var TransitionInterface $service */
            $service = $this->container->get($transition->getServiceId());
            $service->fire($case);
            $this->disableUnitsOfWork($transition, $case);
            $this->enableUnitsOfWork($transition, $case);
            $this->getManager()->flush();
            $this->getManager()->commit();
        } catch (\Exception $e) {
            $this->getManager()->rollback();
            throw $e;
        }
    }

    private function disableUnitsOfWork(Petri\Transition $transition, CaseInterface $case)
    {

    }

    private function enableUnitsOfWork(Petri\Transition $transition, CaseInterface $case)
    {

    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getManager()
    {
        return $this->doctrine->getManager();
    }
}
