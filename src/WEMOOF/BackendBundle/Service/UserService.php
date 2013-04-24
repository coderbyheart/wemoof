<?php

namespace WEMOOF\BackendBundle\Service;

use LiteCQRS\Plugin\CRUD\Model\Commands\UpdateResourceCommand;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;
use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Bus\CommandBus;
use WEMOOF\BackendBundle\Command\RegisterUserCommand;
use WEMOOF\BackendBundle\Command\SendLoginLinkCommand;
use WEMOOF\BackendBundle\Command\SendTemplateMailCommand;
use WEMOOF\BackendBundle\Command\VerifyUserCommand;
use WEMOOF\BackendBundle\Value\EmailValue;
use WEMOOF\BackendBundle\Value\TemplateIdentifierValue;

class UserService
{
    /**
     * @var \LiteCQRS\Bus\CommandBus
     */
    private $commandBus;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param CommandBus $commandBus
     * @param RouterInterface $router
     */
    public function __construct(CommandBus $commandBus, RouterInterface $router)
    {
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function registerUser(RegisterUserCommand $command)
    {
        $createUserCommand = new CreateResourceCommand();
        $createUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $createUserCommand->data = array('email' => $command->email);
        $this->commandBus->handle($createUserCommand);
    }


    public function verifyUser(VerifyUserCommand $command)
    {
        $updateUserCommand = new UpdateResourceCommand();
        $updateUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateUserCommand->id = (string)$command->id;
        $updateUserCommand->data = array('verified' => true);
        $this->commandBus->handle($updateUserCommand);
    }

    public function sendLoginLink(SendLoginLinkCommand $command)
    {
        $generator = new SecureRandom();
        $loginKey = sha1($generator->nextBytes(32));
        $updateUserCommand = new UpdateResourceCommand();
        $updateUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateUserCommand->id = $command->user->getId();
        $updateUserCommand->data = array('loginKey' => $loginKey);
        $this->commandBus->handle($updateUserCommand);

        $this->commandBus->handle(
            SendTemplateMailCommand::create(
                new EmailValue($command->user->getEmail()),
                new TemplateIdentifierValue('WEMOOFBackendBundle:Email:login.txt.twig'),
                array(
                    'user' => $command->user,
                    'loginlink' => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_login', array('id' => $command->user->getId(), 'key' => $loginKey)),
                )));
    }
}