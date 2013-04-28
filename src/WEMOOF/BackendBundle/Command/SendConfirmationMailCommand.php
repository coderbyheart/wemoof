<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Entity\Registration;
use WEMOOF\BackendBundle\Value\EmailValue;
use Symfony\Component\Validator\Constraints as Assert;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;


class SendConfirmationMailCommand
{
    /**
     * @var Registration
     */
    public $registration;

    /**
     * @var SchemeAndHostValue
     */
    public $schemeAndHost;

    /**
     * @param Registration $registration
     * @param SchemeAndHostValue $schemeAndHost
     * @return SendLoginLinkCommand
     */
    public static function create(Registration $registration, SchemeAndHostValue $schemeAndHost)
    {
        $command = new self;
        $command->registration = $registration;
        $command->schemeAndHost = $schemeAndHost;
        return $command;
    }
}
