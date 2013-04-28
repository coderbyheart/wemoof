<?php

namespace WEMOOF\BackendBundle\Command;

use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterEventCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var Event
     */
    public $event;

    /**
     * @param \WEMOOF\BackendBundle\Entity\User $user
     * @param \WEMOOF\BackendBundle\Entity\Event $event
     * @return RegisterEventCommand
     */
    public static function create(User $user, Event $event)
    {
        $command = new self;
        $command->user = $user;
        $command->event = $event;
        return $command;
    }
}
