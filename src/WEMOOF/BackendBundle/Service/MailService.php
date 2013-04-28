<?php

namespace WEMOOF\BackendBundle\Service;

use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Bus\CommandBus;
use Symfony\Bridge\Twig\TwigEngine;
use WEMOOF\BackendBundle\Command\RegisterUserCommand;
use WEMOOF\BackendBundle\Command\SendLoginLinkCommand;
use WEMOOF\BackendBundle\Command\SendTemplateMailCommand;

class MailService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Symfony\Bridge\Twig\TwigEngine
     */
    private $templating;

    /**
     * @var string
     */
    private $mailFromName;

    /**
     * @var string
     */
    private $mailFromEmail;

    /**
     * @param \Swift_Mailer $mailer
     * @param $mailFrom
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $templating, $mailFromEmail, $mailFromName)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->mailFromEmail = $mailFromEmail;
        $this->mailFromName = $mailFromName;
    }

    public function sendTemplateMail(SendTemplateMailCommand $command)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->mailFromEmail, $this->mailFromName);
        $message->setSubject($command->subject->getOrElse('Webmontag Offenbach'))
            ->setTo((string)$command->email)
            ->setBcc('m@wemoof.de')
            ->setBody(
                $this->templating->render((string)$command->template, $command->templateData)
            );
        $this->mailer->send($message);
    }
}
