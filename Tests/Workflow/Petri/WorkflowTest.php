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

        $this->assertFalse($wf->isConnected());
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
                $p = new Place(array('name' => 'place', 'input' => true)),
                $t = new Transition(array('name' => 'trans', 'join' => 'or'))
            )
            ->addLink($t, new Place(array('name' => 'output', 'output' => true)))
        ;

        $this->assertCount(1, $wf->getOutputSet($t));
        $this->assertEmpty($wf->getInputSet($p));
        $this->assertCount(1, $wf->getInputSet($t));
        $this->assertCount(1, $wf->getOutputSet($p));
        $this->assertArrayHasKey('trans', $wf->getOutputSet($p));
        $this->assertArrayHasKey('place', $wf->getInputSet($t));
        $this->assertTrue($wf->isConnected());
        $this->assertTrue($wf->isStronglyConnected());
        $this->assertTrue($wf->hasSpecialPlaces());
        $wf->getNormalizedNet();
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

    public function testRemoveNode()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Place(array('name' => 'place'))
            )
            ->addLink($p, $t)
        ;

        $wf->removeNode($t);
        $wf->removeNode($p);

        $this->assertFalse($wf->hasNode($t));
        $this->assertFalse($wf->hasNode($p));
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotRemoveUnexistingNode()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Place(array('name' => 'place'))
            )
        ;

        $wf->removeNode(new Place(array('name' => 'toto')));
    }

    /**
     * @expectedException \Exception
     */
    public function testFreezeAndModifyThrowsException()
    {
        $wf = new Workflow;
        $wf
            ->addLink(
                $t = new Transition(array('name' => 'trans')),
                $p = new Place(array('name' => 'place'))
            )
        ;

        $this->assertFalse($wf->isFrozen());

        $wf->freeze();

        $wf->addNode(new Place(array('name' => 'added')));
    }
}
