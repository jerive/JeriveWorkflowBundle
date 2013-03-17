<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Jerive\Bundle\WorkflowBundle\Workflow\Transition\TransitionInterface;

/**
 * Using this paper
 * http://martinfowler.workflowpatterns.com/documentation/documents/vanderaalst98application.pdf
 * @author jerome
 */
class WorkflowManager implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * @var CaseInterface
     */
    protected $case;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    public function initialize(WorkflowInterface $workflow, CaseInterface $case)
    {
        if ($workflow->supports($case)) {
            $this->workflow = $workflow;
            $this->case     = $case;

            return $this;
        }

        throw new \RuntimeException('Cannot initialize a workflow with unsuported case');
    }

    /**
     * @param <PlaceInterface array> $entity
     */
    public function getCurrentPlacesAsIds()
    {
        return $this->getManager()->createQuery('
            select p.place
              from JeriveWorkflowBundle:Token t
         left join t.places p
             where t.case     = :ref
               and t.workflow = :wf ')
            ->setParameters(array(
                'ref' => $this->getCaseIdentifier(),
                'wf'  => $this->workflow->getName(),
            ))
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_SCALAR)
        ;
    }

    /**
     * @param CaseInterface $case
     * @return <PlaceInterface array>
     */
    public function getCurrentPlaces()
    {
        $places = array();
        foreach($this->getPlacesAsIds($this->case) as $id) {
            $places[$id] = $this->container->get($id);
        }

        return $places;
    }

    /**
     * @param CaseInterface $case
     * @param PlaceInterface $place
     * @return bool
     */
    public function isInPlace(PlaceInterface $place)
    {
        return $this->getManager()->createQuery('
            select true
              from JeriveWorkflowBundle:Token t
         left join t.places p
             where t.case     = :ref
               and t.workflow = :wf
               and p.place    = :place')
            ->setParameters(array(
                'ref'   => $this->getCaseIdentifier(),
                'place' => $place,
                'wf'    => $this->workflow->getName(),
            ))
            ->getSingleScalarResult()
        ;
    }

    /**
     * Is the transition enabled
     *
     * @param TransitionInterface $transition
     * @param CaseInterface $case
     * @return bool
     */
    public function isEnabled(TransitionInterface $transition)
    {
        return 0 === count(array_diff(
            $this->getPreviousPlaces($transition),
            $this->getCurrentPlacesAsIds()
        ));
    }

    /**
     *
     * @param TransitionInterface $transition
     * @param CaseInterface       $case
     * @return bool
     * @throws \RuntimeException
     */
    public function fire(TransitionInterface $transition)
    {
        if ($this->isEnabled($transition)) {
            $available = $this->getNextPlaces($transition);
            $results   = $transition->fire($this->case, $possible);

            return;
        }

        throw new \RuntimeException('Cannot fire an unabled transition');
    }

    protected function getNextPlaces(TransitionInterface $transition)
    {

    }

    protected function getPreviousPlaces(TransitionInterface $transition)
    {

    }

    protected function getNextTransitions(PlaceInterface $place)
    {

    }

    protected function getPreviousTransitions(PlaceInterface $place)
    {

    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * @param CaseInterface $case
     * @return string
     */
    protected function getCaseIdentifier(CaseInterface $case)
    {
        $meta = $this->getManager()->getClassMetadata(get_class($case));
        $meta->getIdentifierValues($case);
        $utils = new \Doctrine\Common\Util\ClassUtils();
    }
}
