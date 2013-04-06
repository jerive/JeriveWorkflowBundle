<?php

namespace Jerive\Bundle\WorkflowBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Jerive\Bundle\WorkflowBundle\DependencyInjection\Compiler\PetriNetBuildPass;

class JeriveWorkflowBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PetriNetBuildPass());
    }
}
