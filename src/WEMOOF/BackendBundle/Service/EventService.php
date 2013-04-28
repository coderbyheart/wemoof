<?php

namespace WEMOOF\BackendBundle\Service;

use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Plugin\CRUD\Model\Commands\DeleteResourceCommand;
use LiteCQRS\Bus\CommandBus;
use WEMOOF\BackendBundle\Command\RegisterEventCommand;
use WEMOOF\BackendBundle\Command\UnregisterEventCommand;

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
        $createCommand = new CreateResourceCommand();
        $createCommand->class = '\WEMOOF\BackendBundle\Entity\Registration';
        $createCommand->data = array('user' => $command->user, 'event' => $command->event);
        $this->commandBus->handle($createCommand);
    }

    public function unregisterEvent(UnregisterEventCommand $command)
    {
        $deleteCommand = new DeleteResourceCommand();
        $deleteCommand->class = '\WEMOOF\BackendBundle\Entity\Registration';
        $deleteCommand->id = $command->registration->getId();
        $this->commandBus->handle($deleteCommand);
    }
}
