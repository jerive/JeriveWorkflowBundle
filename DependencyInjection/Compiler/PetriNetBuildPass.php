<?php

namespace Jerive\Bundle\WorkflowBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

use Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Transition;
use Jerive\Bundle\WorkflowBundle\Mapping\Annotation\Place;

use Jerive\Bundle\WorkflowBundle\Transition\TransitionInterface;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Description of PetriNetBuildPass
 *
 * @author jerome
 */
class PetriNetBuildPass implements CompilerPassInterface
{
    protected $places = array();

    protected $transitions = array();

    public function process(ContainerBuilder $container)
    {
        $baseAnnotation = 'Jerive\\Bundle\\WorkflowBundle\\Mapping\\Annotation\\';
        $annotationTransition = $baseAnnotation . 'Transition';
        $annotationPlace = $baseAnnotation . 'Place';
        $reader = new AnnotationReader;
        foreach($container->getParameter('kernel.bundles') as $bundleClass) {
            if (is_dir($dir = dirname((new \ReflectionClass($bundleClass))->getFileName()) . '/Workflow')) {
                foreach((new Finder)->files()->name('*.php')->in($dir) as $file) {
                    try {
                        $class = new \ReflectionClass($this->getFirstClassInFile($file));
                        $this->addTransition($class, $reader->getClassAnnotation($class, $annotationTransition));
                        $this->addPlace($class, $reader->getClassAnnotation($class, $annotationPlace));
                    } catch (\ReflectionException $e) { }
                }
            }
        }
    }

    protected function addPlace(\ReflectionClass $class, Place $place = null)
    {
        //TODO
    }

    protected function addTransition(\ReflectionClass $class, Transition $transition = null)
    {
        $baseInterface = 'Jerive\\Bundle\\WorkflowBundle\\Transition\\';

        if ($transition) {
            switch ($transition->trigger) {
                case TransitionInterface::TRIGGER_AUTO:
                    $class->implementsInterface($baseInterface . 'AutomaticTransitionInterface');
                    break;
                default:
                    throw new \Exception(sprintf('Unable to find trigger type %s', $transition->trigger));
            }
        }
    }

    /**
     * Returns the full class name for the first class in the file.
     *
     * @param type $file
     * @return boolean
     */
    protected function getFirstClassInFile($file)
    {
        $tokens = token_get_all(file_get_contents($file));
        $namespace = '';
        foreach($tokens as $key => $token) {
            if ($token[0] === T_NAMESPACE) {
                $namespace = $this->getClassPath($tokens, $key);
            }

            if ($token[0] === T_CLASS) {
                $shortclass = $this->getClassPath($tokens, $key);
                break;
            }
        }

        if (!isset($shortclass)) {
            return false;
        }

        return $namespace . '\\' . $shortclass;
    }

    protected function getClassPath($tokens, $start)
    {
        $key = $start + 1;
        $realToken = '';
        while (!in_array($tokens[$key], array(';', '{'))) {
            if (isset($tokens[$key][0])) {
                if (in_array($tokens[$key][0], array(T_STRING, T_NS_SEPARATOR))) {
                    $realToken .= $tokens[$key][1];
                }
            }
            $key++;
        }

        return $realToken;
    }
}
