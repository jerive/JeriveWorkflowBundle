<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jerive_token")
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $workflow;

    /**
     * A reference to the case (ie entity)
     * (class + ID)
     *
     * @ORM\Column(type="string")
     */
    protected $caseRef;

    /**
     * Current places the case is currently located in
     *
     * @ORM\OneToMany(targetEntity="TokenPlace", mappedBy="token")
     */
    protected $places;
}
