<?php

namespace Jerive\Bundle\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JeriveWorkflowBundle:Default:index.html.twig', array('name' => $name));
    }
}
