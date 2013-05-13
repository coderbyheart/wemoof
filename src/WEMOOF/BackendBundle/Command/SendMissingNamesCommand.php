<?php

namespace WEMOOF\BackendBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;

class SendMissingNamesCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wemoof:sendmissingnames')
            ->setDescription('Send reminder emails about missing names in profile')
            ->addArgument('after', InputArgument::OPTIONAL, 'Only send reminders to users registered after this date');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface $repo */
        $repo = $this->getContainer()->get('wemoof.backend.repo.registration');
        /** @var \LiteCQRS\Bus\CommandBus $commandBus */
        $commandBus = $this->getContainer()->get('command_bus');
        $after      = $input->getArgument('after') ? new \DateTime($input->getArgument('after')) : null;
        foreach ($repo->getMissingNameRegistrations() as $registration) {
            if ($after !== null && $after->diff($registration->getCreated())->invert > 0) {
                if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) $output->writeln(sprintf('Skipping %s', $registration->getUser()->getEmail()));
                continue;
            }
            if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) $output->writeln($registration->getUser()->getEmail());
            $commandBus->handle(SendMissingNameMailCommand::create($registration->getUser(), SchemeAndHostValue::parse($this->getContainer()->getParameter("scheme_and_host"))));
        }
    }
}
