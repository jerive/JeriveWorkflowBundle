<?php

namespace Jerive\Bundle\WorkflowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author jviveret
 */
class ExecuteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jerive:workflow:cron')
            ->setDescription("Execute scheduled Units of Work")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('jerive_workflow.manager');
    }
}
