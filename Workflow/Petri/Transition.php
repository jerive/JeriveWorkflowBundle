<?php

namespace Jerive\Bundle\WorkflowBundle\Workflow\Petri;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Transition
 *
 * @author jerome
 */
class Transition extends Node
{
    const TRIGGER_AUTO = 'auto';

    const TRIGGER_USER = 'user';

    const TRIGGER_CRON = 'cron';

    const TRIGGER_MESS = 'mess';

    const SPLIT_OR     = 'or';

    const SPLIT_AND    = 'and';

    /**
     * @Assert\Type(type="string")
     */
    protected $serviceId;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getTypes")
     */
    protected $type = self::TRIGGER_USER;

    /**
     * @Assert\Choice(callback="getSplits")
     */
    protected $split = self::SPLIT_AND;

    /**
     * @Assert\Choice(callback="getSplits")
     */
    protected $join = self::SPLIT_AND;

    /**
     *
     * @return bool
     */
    public function isExplicitOrJoin()
    {
        return $this->join === self::SPLIT_OR;
    }

    /**
     *
     * @return bool
     */
    public function isExplicitOrSplit()
    {
        return $this->split === self::SPLIT_OR;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public static function getTypes()
    {
        return array(
            self::TRIGGER_AUTO,
            self::TRIGGER_CRON,
            self::TRIGGER_MESS,
            self::TRIGGER_USER,
        );
    }

    public static function getSplits()
    {
        return array(
            self::SPLIT_AND,
            self::SPLIT_OR,
        );
    }
}
