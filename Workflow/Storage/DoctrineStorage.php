<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Storage;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Entity\CaseInterface;
use Jerive\Bundle\WorkflowBundle\Transition\TransitionInterface;

/**
 * Description of DatabaseStorage
 *
 * @author jerome
 */
class DoctrineStorage implements StorageInterface
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var array
     */
    protected $workflows;

    protected $classUnitOfWork;

    protected $classToken;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getEnabledTransitions(Petri\Place $place)
    {

    }

    /**
     * @see {@link StorageInterface::executeActivity }
     * @param Transition $transition
     * @param CaseInterface $case
     * @param TransitionInterface $service
     * @throws \Exception
     */
    public function executeActivity(Petri\Transition $transition, CaseInterface $case, TransitionInterface $service)
    {
        $this->getManager()->beginTransaction();

        try {
            $service->fire($case);
            $this->updateInternalState($transition, $case);
            $this->getManager()->flush();
            $this->getManager()->commit();
        } catch (\Exception $e) {
            $this->getManager()->rollback();
            throw $e;
        }
    }

    public function isEnabled(Petri\Transition $transition, Caseinterface $case)
    {
        $qb = $this->getRepository($case)->createQueryBuilder('c');

    }

    private function updateInternalState(Petri\Transition $transition, CaseInterface $case)
    {
        if ($transition->isExplicitOrJoin()) {

        }

        if ($transition->isExplicitOrSplit()) {

        }

    }

    private function getAlterableOriginSet(Petri\Transition $transition, CaseInterface $case)
    {
        foreach($transition->getInputSet() as $place) {
            foreach($place->getOutputSet() as $toBeDisabled) {

            }
        }
    }

    private function getAlterableTargetSet(Petri\Transition $transition, CaseInterface $case)
    {
        foreach($transition->getOutputSet() as $place) {
            foreach($place->getOutputSet() as $toBeEnabled) {

            }
        }
    }

    public function getAllEnabledTransitions(Petri\Workflow $workflow, CaseInterface $case)
    {

    }

    public function getAvailableUnitsofWork()
    {

    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getManager()
    {
        return $this->doctrine->getManager();
    }

    /**
     * Get entity repository
     *
     * @param CaseInterface $case
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository(CaseInterface $case)
    {
        return $this->getManager()->getRepository(get_class($case));
    }

    /**
     *
     * @param string $unitOfWork
     * @param string $token
     */
    public function setEntityClasses($unitOfWork, $token)
    {
        $this->classUnitOfWork = $unitOfWork;
        $this->classToken      = $token;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getTokenRepository()
    {
        return $this->getManager()->getRepository($this->classToken);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getUnitOfWorkRepository()
    {
        return $this->getManager()->getRepository($this->classUnitOfWork);
    }
}
