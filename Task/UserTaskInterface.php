<?php

namespace Jerive\Bundle\WorkflowBundle\Task;

/**
 *
 */
interface UserTaskInterface extends TaskInterface
{
    public function ask();

    public function validate();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse();
}
