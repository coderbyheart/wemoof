<?php

namespace WEMOOF\BackendBundle\Command;

use PhpOption\Option;
use WEMOOF\BackendBundle\Value\BooleanValue;

class EditProfileCommand
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Option
     */
    public $firstname;

    /**
     * @var Option
     */
    public $lastname;

    /**
     * @var Option
     */
    public $url;

    /**
     * @var Option
     */
    public $twitter;

    /**
     * @var BooleanValue
     */
    public $public;

    /**
     * @var BooleanValue
     */
    public $hasGravatar;

    /**
     * @var Option
     */
    public $description;

    public function __construct()
    {
        $this->public      = BooleanValue::false();
        $this->hasGravatar = BooleanValue::false();
    }
}
