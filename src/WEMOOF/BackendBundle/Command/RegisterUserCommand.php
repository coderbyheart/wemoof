<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Value\EmailValue;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserCommand
{
    /**
     * @var EmailValue
     */
    public $email;
}