<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

/**
 * Description of Workflow
 *
 * @author jerome
 */
class Workflow
{
    /**
     * @var Place
     */
    protected $input;

    /**
     * @var Place
     */
    protected $output;

    /**
     * @var array
     */
    protected $transitions = array();

    /**
     * @var array
     */
    protected $places = array();

    /**
     * @var array
     */
    protected $transitionsToPlaces = array();

    /**
     * @var array
     */
    protected $placesToTransitions = array();

    public function addLink(Node $a, Node $b)
    {
        if (get_class($a) == get_class($b)) {
            throw new \Exception('Cannot link two places nor two transitions');
        }

        $this->addNode($a)->addNode($b);

        if ($a instanceof Transition) {
            $this->transitionsToPlaces[$a->getName()][] = $b->getName();
        } else {
            $this->placesToTransitions[$a->getName()][] = $b->getName();
        }
    }

    public function hasNode(Node $node)
    {
        if ($node instanceof Transition) {
            return isset($this->transitions[$node->getName()]);
        } else {
            return isset($this->places[$node->getName()]);
        }
    }

    public function addNode(Node $node)
    {
        if ($node instanceof Transition) {
            $this->transitions[$node->getName()] = $node;
        } else {
            $this->places[$node->getName()] = $node;
        }

        return $this;
    }

    public function setInput(Place $input)
    {
        $this->input = $input;
        return $this;
    }

    public function setOutput(Place $output)
    {
        $this->input = $output;
        return $this;
    }

    /**
     *
     * @param Node $node
     * @return array<Node>
     */
    public function getInputSet(Node $node)
    {
        $inputSet = array();

        if ($node instanceof Transition) {
            $originalSet = $this->placesToTransitions;
            $getter = 'getPlaceByName';
        } elseif ($node instanceof Place) {
            $originalSet = $this->transitionsToPlaces;
            $getter = 'getTransitionByName';
        }

        foreach($originalSet as $name => $nodes) {
            if (false !== array_search($node->getName(), $nodes)) {
                $inputSet[$name] = $this->$getter($name);
            }
        }

        return $inputSet;
    }

    /**
     *
     * @param Node $node
     * @return array<Node>
     */
    public function getOutputSet(Node $node)
    {
        if ($node instanceof Transition) {
            return array_map(array($this, 'getPlaceByName'), $node->getName());
        } elseif ($node instanceof Place) {
            return array_map(array($this, 'getTransitionByName'), $node->getName());
        }
    }

    /**
     *
     * @param string $name
     * @return Place
     */
    public function getPlaceByName($name)
    {
        return $this->places[$name];
    }

    /**
     *
     * @param string $name
     * @return Transition
     */
    public function getTransitionByName($name)
    {
        return $this->transitions[$name];
    }
}
