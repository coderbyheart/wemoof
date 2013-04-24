<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Value\EmailValue;
use Symfony\Component\Validator\Constraints as Assert;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;

class SendLoginLinkCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var SchemeAndHostValue
     */
    public $schemeAndHost;

    /**
     * @param User $user
     * @param SchemeAndHostValue $schemeAndHost
     * @return SendLoginLinkCommand
     */
    public static function create(User $user, SchemeAndHostValue $schemeAndHost)
    {
        $command = new self;
        $command->user = $user;
        $command->schemeAndHost = $schemeAndHost;
        return $command;
    }
}