<?php

namespace WEMOOF\BackendBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use WEMOOF\BackendBundle\Entity\Registration;
use WEMOOF\BackendBundle\Exception\InvalidArgumentException;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;

class CreateNameTagsCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wemoof:create-nametags')
            ->setDescription('Create Nametages as PDF files')
            ->addArgument('event', InputArgument::REQUIRED, 'Event ID.')
            ->addArgument('template', InputArgument::REQUIRED, 'Template file.')
            ->addArgument('output', InputArgument::REQUIRED, 'Output directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface $repo */
        /** @var \WEMOOF\BackendBundle\Repository\EventRepositoryInterface $eventRepo */
        $repo      = $this->getContainer()->get('wemoof.backend.repo.registration');
        $eventRepo = $this->getContainer()->get('wemoof.backend.repo.event');
        $eventId   = $input->getArgument('event');
        $event     = $eventRepo->getEvent($eventId)->getOrThrow(new InvalidArgumentException(sprintf('Invalid event: %d', $eventId)));
        $env       = new \Twig_Environment(new \Twig_Loader_String());
        $template  = file_get_contents($input->getArgument('template'));
        foreach ($repo->getRegistrationsForEvent($event) as $registration) {
            $output->writeln((string)$registration->getUser());
            $status = null;
            if ($registration->getRole() == Registration::ROLE_SPEAKER) {
                $status = 'WEMO♥F SPEAKER';
            } elseif ($registration->getRole() == Registration::ROLE_TEAM) {
                $status = 'WEMO♥F TEAM';
            }
            // Create bade
            $data          = array(
                'firstname' => $registration->getUser()->getFirstname(),
                'lastname' => $registration->getUser()->getLastname(),
                'title' => $registration->getUser()->getTitle(),
                'twitter' => $registration->getUser()->getTwitter(),
                'tags' => $registration->getUser()->getTags(),
                'status' => $status,
            );
            if (empty($data['firstname'])) continue;
            $badge         = $env->render($template, (array)$data);
            $badgeFileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $registration->getId();
            $badgeSVG      = $badgeFileName . '.svg';
            $badgePDF      = $badgeFileName . '.pdf';
            file_put_contents($badgeSVG, $badge);
            exec(
                sprintf(
                    '`which inkscape` --export-pdf=%s %s',
                    escapeshellarg($badgePDF),
                    escapeshellarg($badgeSVG)
                )
            );
            $printFile = rtrim($input->getArgument('output'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $registration->getId() . '.pdf';
            copy($badgePDF, $printFile);
            unlink($badgePDF);
            unlink($badgeSVG);
        }
    }
}
