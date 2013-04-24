<?php

namespace WEMOOF\BackendBundle\Command;

use PhpOption\None;
use PhpOption\Some;
use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Value\EmailValue;
use Symfony\Component\Validator\Constraints as Assert;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;
use WEMOOF\BackendBundle\Value\TemplateIdentifierValue;

class SendTemplateMailCommand
{
    /**
     * @var EmailValue
     */
    public $email;

    /**
     * @var TemplateIdentifierValue
     */
    public $template;

    /**
     * @var array
     */
    public $templateData;

    /**
     * @var Some
     */
    public $subject;

    /**
     * @param EmailValue $email
     * @param TemplateIdentifierValue $template
     * @param array $templateData
     * @param null $subject
     * @return SendTemplateMailCommand
     */
    public static function create(EmailValue $email, TemplateIdentifierValue $template, Array $templateData, $subject = null)
    {
        $command = new self;
        $command->email = $email;
        $command->template = $template;
        $command->templateData = $templateData;
        $command->subject = Some::fromValue($subject);
        return $command;
    }
}