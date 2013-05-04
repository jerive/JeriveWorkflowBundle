<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Description of Workflow
 *
 * @Assert\Callback(methods={"hasSpecialPlaces", "validateStrongConnectedness"})
 */
class Workflow
{
    /**
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @Assert\NotBlank
     */
    protected $title;

    /**
     * @var Place
     * @Assert\NotBlank()
     */
    protected $input;

    /**
     * @var Place
     * @Assert\NotBlank()
     */
    protected $output;

    /**
     * @var array
     * @Assert\Valid()
     */
    protected $transitions = array();

    /**
     * @var array
     * @Assert\Valid()
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

    /**
     * Inverse relationship
     * @var array
     */
    protected $inverseTransitionsToPlaces = array();

    /**
     * Inverse relationship
     * @var array
     */
    protected $inversePlacesToTransitions = array();

    /**
     * Adds a link between two nodes
     *
     * @param Node $a
     * @param Node $b
     * @return Workflow
     * @throws Exception\IntegrityException
     */
    public function addLink(Node $a, Node $b)
    {
        if ($this->isLinked($a, $b)) {
            throw new Exception\IntegrityException('Trying to link two linked nodes');
        }

        if (!$this->hasNode($a)) {
            $this->addNode($a);
        }

        if (!$this->hasNode($b)) {
            $this->addNode($b);
        }

        if ($a instanceof Transition) {
            $this->transitionsToPlaces[$a->getName()][] = $b->getName();
            $this->inverseTransitionsToPlaces[$b->getName()][] = $a->getName();
        } else {
            $this->placesToTransitions[$a->getName()][] = $b->getName();
            $this->inversePlacesToTransitions[$b->getName()][] = $a->getName();
        }

        return $this;
    }

    /**
     * Are two nodes linked ?
     *
     * @param Node $a
     * @param Node $b
     * @return bool
     * @throws \Exception
     */
    public function isLinked(Node $a, Node $b)
    {
        if (get_class($a) == get_class($b)) {
            throw new \Exception('Cannot link two places nor two transitions');
        }

        return array_search($b->getName(), $this->getSuccessors($a)) !== false;
    }

    /**
     * Does the workflow own the node ?
     *
     * @param Node $node
     * @return bool
     */
    public function hasNode(Node $node)
    {
        if ($node instanceof Transition) {
            return isset($this->transitions[$node->getName()]);
        } else {
            return isset($this->places[$node->getName()]);
        }
    }

    /**
     * Register a node with the workflow
     *
     * @param Node $node
     * @return Workflow
     * @throws Exception\IntegrityException
     */
    public function addNode(Node $node)
    {
        if ($this->hasNode($node)) {
            throw new Exception\IntegrityException(sprintf('Node %s already exists', $node->getName()));
        }

        if ($node instanceof Transition) {
            $this->transitions[$node->getName()] = $node;
        } elseif ($node instanceof Place) {
            $this->places[$node->getName()] = $node;
            if ($node->isInput()) {
                $this->setInput($node);
            }

            if ($node->isOutput()) {
                $this->setOutput($node);
            }
        }

        return $this;
    }

    /**
     * Get the input set for the node
     *
     * @param Node $node
     * @return array<Node>
     */
    public function getInputSet(Node $node)
    {
        return $this->getSet($node, true);
    }

    /**
     * Get the output set for the node
     *
     * @param Node $node
     * @return array<Node>
     */
    public function getOutputSet(Node $node)
    {
        return $this->getSet($node);
    }

    private function getSet(Node $node, $output = false)
    {
        $set    = array();
        $getter = $this->getTargetCallback($node);
        foreach($this->getSuccessors($node, $output) as $name) {
            $set[$name] = $this->$getter($name);
        }

        return $set;
    }

    /**
     * @return Place
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return Place
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Return the place with name $name
     *
     * @param string $name
     * @return Place
     */
    public function getPlaceByName($name)
    {
        return $this->places[$name];
    }

    /**
     * Return the transition with name $name
     *
     * @param string $name
     * @return Transition
     */
    public function getTransitionByName($name)
    {
        return $this->transitions[$name];
    }

    /**
     * Check that this Petri net is connected
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->depthFirstTraversal($this->getInput());
    }

    /**
     * Check that this Petri net is strongly connected
     */
    public function isStronglyConnected()
    {
        return count($this->getStronglyConnectedComponents()) === 1;
    }

    public function validateConnected(ExecutionContext $ec)
    {
        if (!$this->isConnected()) {
            $ec->addViolation('This workflow graph is not connected');
        }
    }

    public function validateStrongConnectedness(ExecutionContext $ec)
    {
        $stronglyConnectedComponents = $this->getStronglyConnectedComponents();
        $count = count($stronglyConnectedComponents);

        if ($count !== 1) {
            $ec->addViolation(sprintf('This workflow graph has %d strongly connected components and therefore is not a WF-net', $count));
        }
    }

    public function hasSpecialPlaces()
    {
        return
            count($this->getOutputSet($this->getOutput())) == 0 &&
            count($this->getInputSet($this->getInput())) == 0
        ;
    }

    private function setInput(Place $input)
    {
        if (isset($this->input)) {
            throw new Exception\IntegrityException('Cannot have two input nodes');
        }

        $this->input = $input;
        return $this;
    }

    private function setOutput(Place $output)
    {
        if (isset($this->output)) {
            throw new Exception\IntegrityException('Cannot have two output nodes');
        }

        $this->output = $output;
        return $this;
    }

    /**
     *
     * @param Node $node
     * @return array<Node>
     */
    private function getSuccessors(Node $node, $inverse = false)
    {
        if ($inverse) {
            $baseSet = $node instanceof Transition ? $this->inversePlacesToTransitions : $this->inverseTransitionsToPlaces;
        } else {
            $baseSet = $node instanceof Transition ? $this->transitionsToPlaces : $this->placesToTransitions;
        }

        if (isset($baseSet[$node->getName()])) {
            return $baseSet[$node->getName()];
        }

        return array();
    }

    /**
     * @param Node $node
     * @return string
     */
    private function getTargetCallback(Node $node)
    {
        return $node instanceof Transition ? 'getPlaceByName' : 'getTransitionByName';
    }

    protected function depthFirstTraversal($places, $transitions = array())
    {
        if (!is_array($places)) {
            $places = array($places);
        }

        $countPlaces      = count($places);
        $countTransitions = count($transitions);

        foreach($places as $place) {
            $transitions = array_merge($transitions, $this->getOutputSet($place));
        }

        foreach($transitions as $transition) {
            $places = array_merge($places, $this->getOutputSet($transition));
        }

        if (count($places) === $countPlaces && count($transitions) === $countTransitions) {
            return count($this->places) === $countPlaces && count($this->transitions) === $countTransitions;
        } else {
            return $this->depthFirstTraversal($places, $transitions);
        }
    }

    /**
     * Implementation of Tarjan's algorithm
     *
     * @see http://fr.wikipedia.org/wiki/Algorithme_de_Tarjan#Pseudo-code
     */
    public function getStronglyConnectedComponents()
    {
        $num   = 0;
        $stack = [];
        $partition = [];

        foreach(array_values($this->places) + array_values($this->transitions) as $node) {
            if (!isset($node->num)) {
                $this->strongConnectednessDepthFirstTraversal($node, $num, $partition, $stack);
            }
        }

        return $partition;
    }

    /**
     * Depth first traversal for Tarjan's algorithm
     *
     * @param \Node $node
     * @param int $num
     * @param array $partition
     * @param array $stack
     * @return array
     */
    private function strongConnectednessDepthFirstTraversal(Node $node, &$num, &$partition, &$stack)
    {
        $node->num = $num;
        $node->numAccessible = $num;
        $node->inStack = true;
        $num++;
        $stack[] = $node;

        $successors = $this->getOutputSet($node);

        /**
         * Strong connectedness is interesting
         * for a workflow graph with this additional link
         */
        if ($node instanceof Place && $node->isOutput()) {
            $successors[] = $this->getInput();
        }

        foreach($successors as $successor) {
            if (!isset($successor->num)) {
                $this->strongConnectednessDepthFirstTraversal($successor, $num, $partition, $stack);
                $node->numAccessible = min($node->numAccessible, $successor->numAccessible);
            } elseif (isset($node->inStack)) {
                $node->numAccessible = min($node->numAccessible, $successor->num);
            }
        }

        if ($node->numAccessible == $successor->num) {
            $set = [];
            do {
                $popped = array_pop($stack);
                unset($popped->inStack);
                $set[] = $popped;
            } while ($node !== $popped);
            $partition[] = $set;
        }
    }
}
