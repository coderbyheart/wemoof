<?php

namespace WEMOOF\BackendBundle\Command;

class SendConfirmationsCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wemoof:sendconfirmations')
            ->setDescription('Send registration confirmation emails');
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        /** @var \WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface $repo */
        $repo = $this->getContainer()->get('wemoof.backend.repo.registration');
        /** @var \LiteCQRS\Bus\CommandBus $commandBus */
        $commandBus = $this->getContainer()->get('command_bus');
        foreach ($repo->getUnconfirmedRegistrations() as $registration) {
            $output->writeln($registration->getUser()->getEmail());
            $commandBus->handle(SendConfirmationMailCommand::create($registration, \WEMOOF\BackendBundle\Value\SchemeAndHostValue::parse($this->getContainer()->getParameter("scheme_and_host"))));
        }
    }
}
