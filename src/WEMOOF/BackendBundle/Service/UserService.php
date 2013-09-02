<?php

namespace WEMOOF\BackendBundle\Service;

use LiteCQRS\Plugin\CRUD\Model\Commands\UpdateResourceCommand;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;
use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Bus\CommandBus;
use WEMOOF\BackendBundle\Command\ClearLoginKeyCommand;
use WEMOOF\BackendBundle\Command\EditProfileCommand;
use WEMOOF\BackendBundle\Command\RegisterUserCommand;
use WEMOOF\BackendBundle\Command\SendLoginLinkCommand;
use WEMOOF\BackendBundle\Command\SendConfirmationMailCommand;
use WEMOOF\BackendBundle\Command\SendMissingNameMailCommand;
use WEMOOF\BackendBundle\Command\SendProfileMailCommand;
use WEMOOF\BackendBundle\Command\SendTemplateMailCommand;
use WEMOOF\BackendBundle\Command\VerifyUserCommand;
use WEMOOF\BackendBundle\Entity\User;
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
        $this->router     = $router;
    }

    public function registerUser(RegisterUserCommand $command)
    {
        $createUserCommand        = new CreateResourceCommand();
        $createUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $createUserCommand->data  = array('email' => $command->email);
        $this->commandBus->handle($createUserCommand);
    }


    public function verifyUser(VerifyUserCommand $command)
    {
        $updateUserCommand        = new UpdateResourceCommand();
        $updateUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateUserCommand->id    = (string)$command->id;
        $updateUserCommand->data  = array('verified' => true);
        $this->commandBus->handle($updateUserCommand);
    }

    public function sendLoginLink(SendLoginLinkCommand $command)
    {
        $generator                = new SecureRandom();
        $loginKey                 = sha1($generator->nextBytes(32));
        $updateUserCommand        = new UpdateResourceCommand();
        $updateUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateUserCommand->id    = $command->user->getId();
        $updateUserCommand->data  = array('loginKey' => $loginKey);
        $this->commandBus->handle($updateUserCommand);

        $this->commandBus->handle(
            SendTemplateMailCommand::create(
                new EmailValue($command->user->getEmail()),
                new TemplateIdentifierValue('WEMOOFBackendBundle:Email:login.txt.twig'),
                array(
                    'user'         => $command->user,
                    'loginlink'    => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_login', array('id' => $command->user->getId(), 'key' => $loginKey)),
                    'newloginlink' => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_index'),
                ),
                'Dein Login-Link für Webmontag Offenbach'));
    }

    public function sendProfileMail(SendProfileMailCommand $command)
    {
        $loginKey = $this->getLoginKeyForUser($command->user);

        $this->commandBus->handle(
            SendTemplateMailCommand::create(
                new EmailValue($command->user->getEmail()),
                new TemplateIdentifierValue('WEMOOFBackendBundle:Email:profile.txt.twig'),
                array(
                    'user'         => $command->user,
                    'loginlink'    => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_login', array('id' => $command->user->getId(), 'key' => $loginKey)),
                    'newloginlink' => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_index'),
                ),
                'Dein Webmontag Offenbach Profil'));
    }


    public function sendMissingNameMail(SendMissingNameMailCommand $command)
    {
        $loginKey = $this->getLoginKeyForUser($command->user);

        $this->commandBus->handle(
            SendTemplateMailCommand::create(
                new EmailValue($command->user->getEmail()),
                new TemplateIdentifierValue('WEMOOFBackendBundle:Email:missingname.txt.twig'),
                array(
                    'user'         => $command->user,
                    'loginlink'    => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_login', array('id' => $command->user->getId(), 'key' => $loginKey)),
                    'newloginlink' => ((string)$command->schemeAndHost) . $this->router->generate('wemoof_index'),
                ),
                'Dein Webmontag Offenbach Namensschild'));
    }

    protected function getLoginKeyForUser(User $user)
    {
        $loginKey = $user->getLoginKey();
        if (empty($loginKey)) {
            $generator                = new SecureRandom();
            $loginKey                 = sha1($generator->nextBytes(32));
            $updateUserCommand        = new UpdateResourceCommand();
            $updateUserCommand->class = '\WEMOOF\BackendBundle\Entity\User';
            $updateUserCommand->id    = $user->getId();
            $updateUserCommand->data  = array('loginKey' => $loginKey);
            $this->commandBus->handle($updateUserCommand);
        }
        return $loginKey;
    }

    public function sendConfirmationMail(SendConfirmationMailCommand $command)
    {
        $updateCommand        = new UpdateResourceCommand();
        $updateCommand->class = '\WEMOOF\BackendBundle\Entity\Registration';
        $updateCommand->id    = $command->registration->getId();
        $updateCommand->data  = array('confirmed' => new \DateTime());
        $this->commandBus->handle($updateCommand);

        $this->commandBus->handle(
            SendTemplateMailCommand::create(
                new EmailValue($command->registration->getUser()->getEmail()),
                new TemplateIdentifierValue('WEMOOFBackendBundle:Email:confirmation.txt.twig'),
                array(
                    'user'          => $command->registration->getUser(),
                    'event'         => $command->registration->getEvent(),
                    'schemeAndHost' => (string)$command->schemeAndHost,
                ),
                sprintf('Deine Registrierung zum Webmontag Offenbach #%d', $command->registration->getEvent()->getId())
            )
        );
    }

    public function clearLoginKey(ClearLoginKeyCommand $command)
    {
        $updateCommand        = new UpdateResourceCommand();
        $updateCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateCommand->id    = (string)$command->id;
        $updateCommand->data  = array('loginKey' => null);
        $this->commandBus->handle($updateCommand);
    }

    public function editProfile(EditProfileCommand $command)
    {
        $updateCommand        = new UpdateResourceCommand();
        $updateCommand->class = '\WEMOOF\BackendBundle\Entity\User';
        $updateCommand->id    = (string)$command->id;
        $updateCommand->data  = array(
            'firstname'   => $command->firstname->isEmpty() ? null : (string)$command->firstname->get(),
            'lastname'    => $command->lastname->isEmpty() ? null : (string)$command->lastname->get(),
            'title'       => $command->title->isEmpty() ? null : (string)$command->title->get(),
            'url'         => $command->url->isEmpty() ? null : (string)$command->url->get(),
            'twitter'     => $command->twitter->isEmpty() ? null : (string)$command->twitter->get(),
            'tags'        => $command->tags->isEmpty() ? null : (string)$command->tags->get(),
            'description' => $command->description->isEmpty() ? null : (string)$command->description->get(),
            'public'      => $command->public->getBoolean(),
            'hasGravatar' => $command->hasGravatar->getBoolean(),
        );
        $this->commandBus->handle($updateCommand);
    }
}
