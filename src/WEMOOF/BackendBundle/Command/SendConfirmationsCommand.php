<?php

namespace WEMOOF\BackendBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;

class SendConfirmationsCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wemoof:sendconfirmations')
            ->setDescription('Send registration confirmation emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface $repo */
        $repo = $this->getContainer()->get('wemoof.backend.repo.registration');
        /** @var \LiteCQRS\Bus\CommandBus $commandBus */
        $commandBus = $this->getContainer()->get('command_bus');
        foreach ($repo->getUnconfirmedRegistrations() as $registration) {
            if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) $output->writeln($registration->getUser()->getEmail());
            $commandBus->handle(SendConfirmationMailCommand::create($registration, SchemeAndHostValue::parse($this->getContainer()->getParameter("scheme_and_host"))));
        }
    }
}
