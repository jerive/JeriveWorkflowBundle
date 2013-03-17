<?php

namespace Jerive\Bundle\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jerive_token_place")
 */
class TokenPlace
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
    protected $place;

    /**
     * @ORM\ManyToOne(targetEntity="Token")
     */
    protected $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $creationDate;
}
