<?php

namespace WEMOOF\BackendBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;

class SendProfileMailsCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wemoof:sendprofilemails')
            ->setDescription('Send profile reminder emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \WEMOOF\BackendBundle\Repository\UserRepositoryInterface $repo */
        $repo = $this->getContainer()->get('wemoof.backend.repo.user');
        /** @var \LiteCQRS\Bus\CommandBus $commandBus */
        $commandBus = $this->getContainer()->get('command_bus');
        foreach ($repo->getUsersWithoutPublicProfile() as $user) {
            if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) $output->writeln($user->getEmail());
            $commandBus->handle(SendProfileMailCommand::create($user, SchemeAndHostValue::parse($this->getContainer()->getParameter("scheme_and_host"))));
        }
    }
}
