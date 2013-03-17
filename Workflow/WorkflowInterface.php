<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow;

/**
 * Description of PlaceInterface
 *
 * @author jerome
 */
interface WorkflowInterface
{
    /**
     * @return string
     */
    public function getName();

    public function supports(CaseInterface $case);
}
