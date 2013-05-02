<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Workflow
 *
 * @Assert\Callback(methods={"validateConnex"})
 */
class Workflow
{
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
        } else {
            $this->placesToTransitions[$a->getName()][] = $b->getName();
        }

        return $this;
    }

    public function isLinked(Node $a, Node $b)
    {
        if (get_class($a) == get_class($b)) {
            throw new \Exception('Cannot link two places nor two transitions');
        }

        return array_search($b->getName(), $this->getOriginalSet($a)) !== false
        ;
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

    public function setInput(Place $input)
    {
        if (isset($this->input)) {
            throw new Exception\IntegrityException('Cannot have two input nodes');
        }

        $this->input = $input;
        return $this;
    }

    public function setOutput(Place $output)
    {
        if (isset($this->output)) {
            throw new Exception\IntegrityException('Cannot have two output nodes');
        }

        $this->output = $output;
        return $this;
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
     *
     * @param Node $node
     * @return array<Node>
     */
    public function getInputSet(Node $node)
    {
        $inputSet = array();
        $getter   = $this->getTargetCallback($node);

        foreach($this->getOriginalFullSet($node) as $name => $nodes) {
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
        $outputSet = array();
        $getter    = $this->getTargetCallback($node);
        foreach($this->getOriginalSet($node) as $name) {
            $outputSet[$name] = $this->$getter($name);
        }

        return $outputSet;
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

    /**
     *
     * @param Node $node
     * @return array<Node>
     */
    protected function getOriginalSet(Node $node)
    {
        $baseSet = $node instanceof Transition ? $this->transitionsToPlaces : $this->placesToTransitions;

        if (isset($baseSet[$node->getName()])) {
            return $baseSet[$node->getName()];
        }

        return array();
    }

    protected function getOriginalFullSet(Node $node)
    {
        return $node instanceof Transition ? $this->placesToTransitions : $this->transitionsToPlaces;
    }

    /**
     * @param Node $node
     * @return string
     */
    protected function getTargetCallback(Node $node)
    {
        return $node instanceof Transition ? 'getPlaceByName' : 'getTransitionByName';
    }

    public function validateConnex()
    {

    }
}
