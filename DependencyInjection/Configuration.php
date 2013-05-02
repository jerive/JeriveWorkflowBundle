<?php

namespace Jerive\Bundle\WorkflowBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $classValidate = function($class) {
            if (!class_exists($class)) {
                throw new \Exception(sprintf('class %s dos not exist', $class));
            }
            return $class;
        };

        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('jerive_workflow');
        $root->children()
            ->arrayNode('workflows')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->prototype('array')
                ->children()
                    ->scalarNode('class')->isRequired()->validate()
                        ->ifString()->then($classValidate)->end()->end()
                    ->scalarNode('alias')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
            ->enumNode('resource_attribution')->values(array('pull', 'push'))->defaultValue('push')->end()
            ->arrayNode('classes')
                ->isRequired()
                ->children()
                    ->scalarNode('unit_of_work')->isRequired()->validate()
                        ->ifString()->then($classValidate)->end()->end()
                    ->scalarNode('token')->isRequired()->validate()
                        ->ifString()->then($classValidate)->end()->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
