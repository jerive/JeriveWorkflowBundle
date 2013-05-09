<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Storage;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri as Petri;
use Jerive\Bundle\WorkflowBundle\Entity\CaseInterface;
use Jerive\Bundle\WorkflowBundle\Task\TaskInterface;

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

    public function getEnabledTasks(Petri\Place $place)
    {

    }

    /**
     * @see {@link StorageInterface::executeActivity }
     * @param Task $task
     * @param CaseInterface $case
     * @param TaskInterface $service
     * @throws \Exception
     */
    public function executeActivity(Petri\Task $task, CaseInterface $case, TaskInterface $service)
    {
        $this->getManager()->beginTransaction();

        try {
            $service->fire($case);
            $this->updateInternalState($task, $case);
            $this->getManager()->flush();
            $this->getManager()->commit();
        } catch (\Exception $e) {
            $this->getManager()->rollback();
            throw $e;
        }
    }

    public function isEnabled(Petri\Task $task, Caseinterface $case)
    {
        $qb = $this->getRepository($case)->createQueryBuilder('c');

    }

    private function updateInternalState(Petri\Task $task, CaseInterface $case)
    {
        if ($task->isExplicitOrJoin()) {

        }

        if ($task->isExplicitOrSplit()) {

        }

    }

    private function getAlterableOriginSet(Petri\Task $task, CaseInterface $case)
    {
        foreach($task->getInputSet() as $place) {
            foreach($place->getOutputSet() as $toBeDisabled) {

            }
        }
    }

    private function getAlterableTargetSet(Petri\Task $task, CaseInterface $case)
    {
        foreach($task->getOutputSet() as $place) {
            foreach($place->getOutputSet() as $toBeEnabled) {

            }
        }
    }

    public function getAllEnabledTasks(Petri\Workflow $workflow, CaseInterface $case)
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
