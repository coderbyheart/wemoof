<?php

namespace WEMOOF\BackendBundle\Service;

use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Bus\CommandBus;
use WEMOOF\BackendBundle\Command\RegisterEventCommand;

class EventService
{
    /**
     * @var \LiteCQRS\Bus\CommandBus
     */
    private $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function registerEvent(RegisterEventCommand $command)
    {
        $createUserCommand = new CreateResourceCommand();
        $createUserCommand->class = '\WEMOOF\BackendBundle\Entity\Registration';
        $createUserCommand->data = array('user' => $command->user, 'event' => $command->event);
        $this->commandBus->handle($createUserCommand);
    }
}
