<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

/**
 * Description of HasCaseInterface
 *
 * @author jerome
 */
interface HasCaseInterface
{
    /**
     * @return CaseInterface
     */
    public function getCase();

    /**
     *
     * @param CaseInterface $case
     */
    public function setCase(CaseInterface $case);
}
