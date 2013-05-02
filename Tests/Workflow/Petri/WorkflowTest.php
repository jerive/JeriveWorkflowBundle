<?php

namespace Jerive\Bundle\WorkflowBundle\Tests\Workflow\Petri;

use Jerive\Bundle\WorkflowBundle\Workflow\Petri\Workflow;
use Jerive\Bundle\WorkflowBundle\Workflow\Petri\Transition;
use Jerive\Bundle\WorkflowBundle\Workflow\Petri\Place;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddInput()
    {
        $wf = new Workflow;
        $wf
            ->addNode($i = new Place(array('name' => 'toto', 'input' => true)))
            ->addNode($o = new Place(array('name' => 'lolo', 'output' => true)))
        ;

        $this->assertEquals('toto', $wf->getInput()->getName());
        $this->assertEquals('lolo', $wf->getOutput()->getName());
    }

    /**
     * @expectedException \Jerive\Bundle\WorkflowBundle\Workflow\Petri\Exception\IntegrityException
     */
    public function testAddTwoInputsWillFail()
    {
        $wf = new Workflow;
        $wf
            ->addNode($i = new Place(array('name' => 'toto', 'input' => true)))
            ->addNode($o = new Place(array('name' => 'lolo', 'input' => true)))
        ;
    }

    /**
     * @expectedException \Jerive\Bundle\WorkflowBundle\Workflow\Petri\Exception\IntegrityException
     */
    public function testAddTwoOutputsWillFail()
    {
        $wf = new Workflow;
        $wf
            ->addNode($i = new Place(array('name' => 'toto', 'output' => true)))
            ->addNode($o = new Place(array('name' => 'lolo', 'output' => true)))
        ;
    }

    /**
     * @expectedException \Jerive\Bundle\WorkflowBundle\Workflow\Petri\Exception\IntegrityException
     */
    public function testAddTwoSameNameWillFail()
    {
        $wf = new Workflow;
        $wf
            ->addNode($i = new Place(array('name' => 'toto')))
            ->addNode($o = new Place(array('name' => 'toto')))
        ;
    }

    /**
     * @expectedException \Jerive\Bundle\WorkflowBundle\Workflow\Petri\Exception\IntegrityException
     */
    public function testAddTwoTransitionsSameNameWillFail()
    {
        $wf = new Workflow;
        $wf
            ->addNode($i = new Transition(array('name' => 'toto')))
            ->addNode($o = new Transition(array('name' => 'toto')))
        ;
    }


    public function testLinkAndCheckOutput()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Place(array('name' => 'place'))
            )
        ;

        $this->assertEmpty($wf->getInputSet($t));
        $this->assertEmpty($wf->getOutputSet($p));
        $this->assertCount(1, $wf->getOutputSet($t));
        $this->assertCount(1, $wf->getInputSet($p));
        $this->assertArrayHasKey('trans', $wf->getInputSet($p));
        $this->assertArrayHasKey('place', $wf->getOutputSet($t));
    }

    /**
     * @expectedException \Exception
     */
    public function testLinkFailsForSameClass()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Transition(array('name' => 'place'))
            )
        ;
    }

    /**
     * @expectedException \Exception
     */
    public function testLinkFailsForAlreadyExists()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Place(array('name' => 'place'))
            )
            ->addLink($t, $p)
        ;
    }
}
