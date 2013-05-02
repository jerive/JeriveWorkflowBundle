<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Workflow
 *
 * @author jerome
 */
class Node
{
    /**
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     */
    protected $title;

    public function __construct($options)
    {
        foreach(get_object_vars($this) as $key => $val) {
            $this->$key = isset($options[$key]) ? $options[$key] : $val;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    protected function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
