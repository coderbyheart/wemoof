<?php

namespace WEMOOF\WebBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use LiteCQRS\Bus\CommandBus;
use LiteCQRS\Bus\EventExecutionFailed;
use LiteCQRS\Plugin\CRUD\Model\Commands\UpdateResourceCommand;
use PhpOption\None;
use PhpOption\Some;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use WEMOOF\BackendBundle\Command\SendLoginLinkCommand;
use WEMOOF\BackendBundle\Command\VerifyUserCommand;
use WEMOOF\BackendBundle\Repository\EventRepositoryInterface;
use WEMOOF\BackendBundle\Repository\TalkRepositoryInterface;
use WEMOOF\BackendBundle\Repository\UserRepositoryInterface;
use WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface;
use WEMOOF\BackendBundle\Command\RegisterUserCommand;
use WEMOOF\BackendBundle\Command\RegisterEventCommand;
use WEMOOF\BackendBundle\Command\UnregisterEventCommand;
use WEMOOF\BackendBundle\Value\IdValue;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;
use WEMOOF\WebBundle\Form\RegisterType;
use WEMOOF\WebBundle\Form\RegisterEventType;
use WEMOOF\WebBundle\Form\UnregisterEventType;
use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route(service="wemoof.web.controller.web")
 *
 * FIXME: Tests
 */
class WebController
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;

    /**
     * @var TalkRepositoryInterface
     */
    private $talkRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var HttpKernelInterface
     */
    private $httpKernel;

    /**
     * @var \LiteCQRS\Bus\CommandBus
     */
    private $commandBus;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    public function __construct(Request $request, FormFactoryInterface $formFactory, ObjectManager $objectManager, EventRepositoryInterface $eventRepository, TalkRepositoryInterface $talkRepository, UserRepositoryInterface $userRepository, RegistrationRepositoryInterface $registrationRepository, HttpKernelInterface $httpKernel, CommandBus $commandBus, RouterInterface $router)
    {
        $this->request = $request;
        $this->formFactory = $formFactory;
        $this->objectManager = $objectManager;
        $this->eventRepository = $eventRepository;
        $this->talkRepository = $talkRepository;
        $this->userRepository = $userRepository;
        $this->registrationRepository = $registrationRepository;
        $this->httpKernel = $httpKernel;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    /**
     * @Route("/", name="wemoof_index")
     * @Template()
     */
    public function indexAction()
    {
        $event = $this->eventRepository->getNextEvent()->get();
        return $this->forward('wemoof.web.controller.web:eventAction', array('id' => $event->getId()));
    }

    /**
     * @Route("/register", name="wemoof_register")
     * @Template()
     */
    public function registerAction()
    {
        $form = $this->formFactory->create(new RegisterType(), new RegisterUserCommand());

        if (!$this->request->isMethod('POST')) return array(
            'form' => $form->createView(),
        );

        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if (!$form->isValid()) return array(
                'form' => $form->createView(),
            );
        }

        /** @var RegisterUserCommand $command */
        $command = $form->getData();
        $someUser = Some::fromValue($this->userRepository->findOneByEmail($command->email));
        if ($someUser->isEmpty()) {
            $this->commandBus->handle($form->getData());
            $someUser = Some::fromValue($this->userRepository->findOneByEmail($command->email));
        }
        $user = $someUser->getOrThrow(new HttpException(500, "Could not create user."));

        $this->commandBus->handle(SendLoginLinkCommand::create($user, new SchemeAndHostValue($this->request->getSchemeAndHttpHost())));

        return array(
            'user' => $user
        );
    }

    /**
     * @Route("/dashboard", name="wemoof_dashboard")
     * @Template()
     */
    public function dashboardAction()
    {
        $user = $this->getUser();
        $registrations = $this->registrationRepository->getRegistrations($this->getUser());
        $registeredEvents = array_map(function (Registration $registration) {
            return $registration->getEvent()->getId();
        }, $registrations);
        $registration2Events = array();
        foreach($registrations as $registration) {
            $registration2Events[$registration->getEvent()->getId()] = $registration;
        }
        $registerableEvents = array();
        $unregisterableEvents = array();
        foreach ($this->eventRepository->getRegisterableEvents() as $event) {
            if (in_array($event->getId(), $registeredEvents)) {
                $registration = $registration2Events[$event->getId()];
                $form = $this->formFactory->create(new UnregisterEventType(), UnregisterEventCommand::create($registration));
                $unregisterableEvents[] = array(
                    'form' => $form->createView(),
                    'event' => $event,
                    'registration' => $registration,
                );
            } else {
                $form = $this->formFactory->create(new RegisterEventType(), RegisterEventCommand::create($user, $event));
                $registerableEvents[] = array(
                    'form' => $form->createView(),
                    'event' => $event
                );
            }
        }

        return array(
            'user' => $user,
            'registerableEvents' => $registerableEvents,
            'unregisterableEvents' => $unregisterableEvents,
        );
    }

    /**
     * @Route("/logout", name="wemoof_logout")
     */
    public function logoutAction()
    {
        $session = new Session();
        $session->start();
        $session->invalidate();
        return new RedirectResponse($this->router->generate('wemoof_index'));
    }

    /**
     * @Route("/{id}", name="wemoof_event")
     * @Template()
     */
    public function eventAction($id)
    {
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));
        $talks = $this->talkRepository->getTalksForEvent($event);
        return array(
            'form' => $this->formFactory->create(new RegisterType(), new RegisterUserCommand())->createView(),
            'event' => $event,
            'talks' => $talks,
            'missing' => array_fill(0, 6 - count($talks), 1),
        );
    }

    /**
     * @Route("/{id}/signup", name="wemoof_signup")
     * @Template()
     */
    public function signupAction($id)
    {
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));

        $form = $this->formFactory->create(new RegisterType(), new User());
        $created = false;
        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $this->objectManager->persist($form->getData());
                $this->objectManager->flush();
                $created = true;
            }
        }

        return array(
            'form' => $form->createView(),
            'signup' => $created,
            'event' => $event,
        );
    }

    /**
     * @Route("/{id}/register", name="wemoof_register_event", methods={"POST"})
     * @Template()
     */
    public function registerEventAction($id)
    {
        /** @var Event $event  */
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));
        $user = $this->getUser();
        if ($event->getNumTicketsAvailable() <= 0) throw new ForbiddenHttpException(sprintf("Event %d is sold out.", $event->getId()));

        $command = RegisterEventCommand::create($user, $event);
        $form = $this->formFactory->create(new RegisterEventType(), $command);
        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $this->commandBus->handle($command);
                $this->addMessage("Du wurdest erfolgreich angemeldet.");
            }
        }

        return new RedirectResponse($this->router->generate('wemoof_dashboard'));
    }

    /**
     * @Route("/{id}/presse", name="wemoof_presse")
     * @Template()
     */
    public function presseAction($id)
    {
        return $this->eventAction($id);
    }

    /**
     * @Route("/registration/{id}", name="wemoof_unregister_event", methods={"POST", "DELETE"})
     * @Template()
     */
    public function unregisterEventAction($id)
    {
        $registration = $this->registrationRepository->getRegistration($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown registration: %d", $id)));
        if ($registration->getUser()->getId() !== $this->getUser()->getId()) throw new ForbiddenHttpException();
        $command = UnregisterEventCommand::create($registration);
        $form = $this->formFactory->create(new UnregisterEventType(), $command);
        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $this->commandBus->handle($command);
                $this->addMessage("Du wurdest erfolgreich abgemeldet.");
            }
        }

        return new RedirectResponse($this->router->generate('wemoof_dashboard'));
    }

    /**
     * @Route("/talk/{slug}/{id}", name="wemoof_talk")
     * @Template()
     */
    public function talkAction($slug, $id)
    {
        $talk = $this->talkRepository->getTalk($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown talk: %d", $id)));
        return array(
            'event' => $talk->getEvent(),
            'talk' => $talk,
        );

    }

    /**
     * @Route("/speaker/{slug}/{id}", name="wemoof_speaker")
     * @Template()
     */
    public function speakerAction($slug, $id)
    {
        return $this->forward('wemoof.web.controller.web:userAction', array('id' => $id, 'slug' => $slug));
    }

    /**
     * @Route("/user/{slug}/{id}", name="wemoof_user")
     * @Template()
     */
    public function userAction($slug, $id)
    {
        $user = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        $talks = $this->talkRepository->getTalksForUser($user);
        return array(
            'user' => $user,
            'talks' => $talks,
        );
    }

    protected function forward($controller, array $attributes = array(), array $query = array())
    {
        $attributes['_controller'] = $controller;
        $subRequest = $this->request->duplicate($query, null, $attributes);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    public function eventExecutionFailed(EventExecutionFailed $event)
    {

    }

    /**
     * @Route("/login/{id}/{key}", name="wemoof_login")
     * @param $id
     * @param $key
     * @return RedirectResponse
     */
    public function loginAction($id, $key)
    {
        /** @var User $user */
        $user = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        if (!StringUtils::equals($user->getLoginKey(), $key)) throw new AccessDeniedHttpException("Invalid credentials.");

        if (!$user->isVerified()) {
            $this->commandBus->handle(VerifyUserCommand::create(new IdValue($user->getId())));
        }

        $session = new Session();
        $session->start();
        $session->set('user_id', $user->getId());

        return new RedirectResponse($this->router->generate('wemoof_dashboard'));
    }

    /**
     * @return User
     * @throws NotFoundHttpException
     */
    private function getUser()
    {
        $session = $this->getSession();
        $id = $session->get('user_id');
        $user = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        return $user;
    }

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private function getSession()
    {
        if ($this->session === null) {
            $this->session = new Session();
            $this->session->start();
        }
        return $this->session;
    }

    /**
     * @param string $message
     */
    private function addMessage($message)
    {
        $session = $this->getSession();
        $session->getFlashBag()->add('notice', $message);
    }
}
