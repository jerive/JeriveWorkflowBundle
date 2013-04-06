<?php

namespace Jerive\Bundle\WorkflowBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Place extends Annotation
{

    /** @var string */
    public $serviceId;
    /** @var array<\Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Place> */
    public $output = array();
}
