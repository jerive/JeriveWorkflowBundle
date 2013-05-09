<?php

namespace Jerive\Bundle\WorkflowBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Condition extends Annotation
{
    /** @var string */
    public $serviceId = null;
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Task> */
    public $output = array();
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Task> */
    public $input = array();
}
