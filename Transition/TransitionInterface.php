<?php

namespace Jerive\Bundle\WorkflowBundle\Transition;

/**
 * Description of TransitionInterface
 *
 * @author jerome
 */
interface TransitionInterface
{
    const TRIGGER_AUTO = 'auto';

    const TRIGGER_USER = 'user';

    const TRIGGER_CRON = 'cron';

    const TRIGGER_MESS = 'mess';

    const SPLIT_OR     = 'or';

    const SPLIT_AND    = 'and';

    public function fire(CaseInterface $case);
}
