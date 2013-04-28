<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Entity\Registration;

class UnregisterEventCommand
{
    /**
     * @var Registration
     */
    public $registration;

    /**
     * @param \WEMOOF\BackendBundle\Entity\Registration $registration
     * @return UnregisterEventCommand
     */
    public static function create(Registration $registration)
    {
        $command = new self;
        $command->registration = $registration;
        return $command;
    }
}
