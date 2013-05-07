<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Value\IdValue;
use Symfony\Component\Validator\Constraints as Assert;

class ClearLoginKeyCommand
{
    /**
     * @var IdValue
     */
    public $id;

    /**
     * @param IdValue $id
     * @return ClearLoginKeyCommand
     */
    public static function create(IdValue $id)
    {
        $command     = new self;
        $command->id = $id;
        return $command;
    }
}