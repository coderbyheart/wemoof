<?php

namespace WEMOOF\WebBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use LiteCQRS\Bus\CommandBus;
use LiteCQRS\Bus\EventExecutionFailed;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use WEMOOF\BackendBundle\Command\ClearLoginKeyCommand;
use WEMOOF\BackendBundle\Command\SendLoginLinkCommand;
use WEMOOF\BackendBundle\Command\VerifyUserCommand;
use WEMOOF\BackendBundle\Command\EditProfileCommand;
use WEMOOF\BackendBundle\Entity\Event;
use WEMOOF\BackendBundle\Repository\EventRepositoryInterface;
use WEMOOF\BackendBundle\Repository\TalkRepositoryInterface;
use WEMOOF\BackendBundle\Repository\UserRepositoryInterface;
use WEMOOF\BackendBundle\Repository\RegistrationRepositoryInterface;
use WEMOOF\BackendBundle\Command\RegisterUserCommand;
use WEMOOF\BackendBundle\Command\RegisterEventCommand;
use WEMOOF\BackendBundle\Command\UnregisterEventCommand;
use WEMOOF\BackendBundle\Value\BooleanValue;
use WEMOOF\BackendBundle\Value\IdValue;
use WEMOOF\BackendBundle\Value\MarkdownTextValue;
use WEMOOF\BackendBundle\Value\NameValue;
use WEMOOF\BackendBundle\Value\PlainTextValue;
use WEMOOF\BackendBundle\Value\SchemeAndHostValue;
use WEMOOF\BackendBundle\Value\TwitterHandleValue;
use WEMOOF\BackendBundle\Value\URLValue;
use WEMOOF\WebBundle\Form\EditProfileType;
use WEMOOF\WebBundle\Form\RegisterType;
use WEMOOF\WebBundle\Form\RegisterEventType;
use WEMOOF\WebBundle\Form\UnregisterEventType;
use WEMOOF\BackendBundle\Entity\User;
use WEMOOF\BackendBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use WEMOOF\WebBundle\Model\EditProfileModel;
use WEMOOF\WebBundle\Slugger;

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

    /**
     * @var integer
     */
    private $talksPerEvent;

    public function __construct(Request $request, FormFactoryInterface $formFactory, ObjectManager $objectManager, EventRepositoryInterface $eventRepository, TalkRepositoryInterface $talkRepository, UserRepositoryInterface $userRepository, RegistrationRepositoryInterface $registrationRepository, HttpKernelInterface $httpKernel, CommandBus $commandBus, RouterInterface $router, $talksPerEvent)
    {
        $this->request                = $request;
        $this->formFactory            = $formFactory;
        $this->objectManager          = $objectManager;
        $this->eventRepository        = $eventRepository;
        $this->talkRepository         = $talkRepository;
        $this->userRepository         = $userRepository;
        $this->registrationRepository = $registrationRepository;
        $this->httpKernel             = $httpKernel;
        $this->commandBus             = $commandBus;
        $this->router                 = $router;
        $this->talksPerEvent          = $talksPerEvent;
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

    protected function forward($controller, array $attributes = array(), array $query = array())
    {
        $attributes['_controller'] = $controller;
        $subRequest                = $this->request->duplicate($query, null, $attributes);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * Renders the team page.
     *
     * @param Request $request
     * @Route("/team", name="wemoof_team")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function teamAction(Request $request)
    {
        return array(
            'event' => $event = $this->eventRepository->getNextEvent()->get(),
        );
    }

    /**
     * Renders the contact page.
     *
     * @param Request $request
     * @Route("/contact", name="wemoof_contact")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function contactAction(Request $request)
    {
        return array(
            'event' => $event = $this->eventRepository->getNextEvent()->get(),
        );
    }

    /**
     * Renders the faq page.
     *
     * @param Request $request
     * @Route("/faq", name="wemoof_faq")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function faqAction(Request $request)
    {
        return array(
            'event' => $event = $this->eventRepository->getNextEvent()->get(),
        );
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
        $command  = $form->getData();
        $someUser = Some::fromValue($this->userRepository->findOneByEmail($command->email));
        if ($someUser->isEmpty()) {
            $this->commandBus->handle($form->getData());
            $someUser = Some::fromValue($this->userRepository->findOneByEmail($command->email));
        }
        $user = $someUser->getOrThrow(new HttpException(500, "Could not create user."));

        $this->commandBus->handle(SendLoginLinkCommand::create($user, new SchemeAndHostValue($this->request->getSchemeAndHttpHost())));

        $this->addMessage(
            sprintf(
                'Bitte folge den Anweisung in der E-Mail, die gerade an %s verschickt wurde.',
                $command->email
            )
        );

        return new RedirectResponse($this->router->generate('wemoof_index'));
    }

    /**
     * @param string $message
     */
    private function addMessage($message)
    {
        $session = $this->getSession();
        $session->getFlashBag()->add('notice', $message);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    private function getSession()
    {
        return $this->request->getSession();
    }

    /**
     * @Route("/profile", name="wemoof_edit_profile")
     * @Template()
     */
    public function profileAction()
    {
        $user = $this->getUser()->getOrThrow(new AccessDeniedHttpException('Not logged in.'));

        $editProfileForm = $this->formFactory->create(new EditProfileType(), EditProfileModel::factory($user));
        if ($this->request->isMethod('POST')) {
            $editProfileForm->bind($this->request);
            if ($editProfileForm->isValid()) {
                /** @var EditProfileModel $model */
                $model                           = $editProfileForm->getData();
                $editProfileCommand              = new EditProfileCommand();
                $editProfileCommand->id          = new IdValue($user->getId());
                $description                     = $model->getDescription();
                $title                           = $model->getTitle();
                $firstname                       = $model->getFirstname();
                $lastname                        = $model->getLastname();
                $tags                            = $model->getTags();
                $editProfileCommand->description = empty($description) ? None::create() : Some::create(MarkdownTextValue::parse($description));
                $editProfileCommand->firstname   = empty($firstname) ? None::create() : Some::create(NameValue::parse($firstname));
                $editProfileCommand->lastname    = empty($lastname) ? None::create() : Some::create(NameValue::parse($lastname));
                $editProfileCommand->title       = empty($title) ? None::create() : Some::create(PlainTextValue::parse($title));
                $editProfileCommand->tags        = empty($tags) ? None::create() : Some::create(PlainTextValue::parse($tags));
                $editProfileCommand->hasGravatar = new BooleanValue($model->hasGravatar);
                $editProfileCommand->public      = new BooleanValue($model->public);
                $editProfileCommand->twitter     = empty($model->twitter) ? None::create() : Some::create(TwitterHandleValue::parse($model->twitter));
                $editProfileCommand->url         = empty($model->url) ? None::create() : Some::create(URLValue::parse($model->url));
                $this->commandBus->handle($editProfileCommand);
                $this->addMessage("Profil aktualisiert.");
                $editProfileForm = $this->formFactory->create(new EditProfileType(), EditProfileModel::factory($user));
            }
        }

        return array(
            'user'            => $user,
            'editProfileForm' => $editProfileForm->createView(),
        );
    }

    /**
     * @return Option
     */
    private function getUser()
    {
        $session = $this->getSession();
        $id      = $session->get('user_id');
        $user    = $this->userRepository->getUser($id);
        return $user;
    }

    /**
     * @Route("/logout", name="wemoof_logout")
     */
    public function logoutAction()
    {
        $session = $this->getSession();
        $session->invalidate();
        $this->addMessage('Bis bald!');
        return new RedirectResponse($this->router->generate('wemoof_index'));
    }

    /**
     * @Route("/talks", name="wemoof_talks")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function talksAction()
    {
        $talks = $this->talkRepository->getTalks();
        return array(
            'talks'  => $talks,
        );

    }

    /**
     * @Route("/~{id}", name="wemoof_user_short")
     * @Template()
     */
    public function userIdAction($id)
    {
        $user    = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        $slugger = new Slugger();
        return new RedirectResponse($this->router->generate('wemoof_user', array('id' => $id, 'slug' => $slugger->slugify((string)$user))));
    }

    /**
     * @Route("/{id}/signup", name="wemoof_signup")
     * @Template()
     */
    public function signupAction($id)
    {
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));

        $form    = $this->formFactory->create(new RegisterType(), new User());
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
            'form'   => $form->createView(),
            'signup' => $created,
            'event'  => $event,
        );
    }

    /**
     * @Route("/{id}/register", name="wemoof_register_event", methods={"POST"})
     * @Template()
     */
    public function registerEventAction($id)
    {
        /** @var Event $event */
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));
        $user  = $this->getUser()->getOrThrow(new AccessDeniedHttpException('Not logged in.'));
        if ($event->getNumTicketsAvailable() <= 0) throw new AccessDeniedHttpException(sprintf("Event %d is sold out.", $event->getId()));

        $command = RegisterEventCommand::create($user, $event);
        $form    = $this->formFactory->create(new RegisterEventType(), $command);
        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $this->commandBus->handle($command);
                $this->addMessage("Du wurdest erfolgreich angemeldet.");
            }
        }

        return new RedirectResponse($this->router->generate('wemoof_index'));
    }

    /**
     * @Route("/{id}/presse", name="wemoof_presse")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function presseAction($id)
    {
        return $this->eventAction($id);
    }

    /**
     * @Route("/{id}", name="wemoof_event")
     * @Template()
     */
    public function eventAction($id)
    {
        $event         = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));
        $talks         = $this->talkRepository->getTalksForEvent($event);
        $spotlights    = $this->talkRepository->getSpotlightsForEvent($event);
        $missing       = count($talks) < $this->talksPerEvent ? array_fill(0, $this->talksPerEvent - count($talks), 1) : array();
        $registrations = $this->registrationRepository->getGuestsForEvent($event);
        $user          = $this->getUser();
        shuffle($talks);
        shuffle($spotlights);

        $data = array(
            'form'          => $this->formFactory->create(new RegisterType(), new RegisterUserCommand())->createView(),
            'event'         => $event,
            'registrations' => $registrations,
            'talks'         => $talks,
            'spotlights'    => $spotlights,
            'missing'       => $missing,
            'pastEvents'    => $this->eventRepository->getPastEvents(),
        );


        if ($user->isDefined()) {
            $registrations    = $this->registrationRepository->getRegistrationsForUser($user->get());
            $registeredEvents = array_map(function (Registration $registration) {
                return $registration->getEvent()->getId();
            }, $registrations);

            $registerableEvents = array();
            foreach ($this->eventRepository->getRegisterableEvents() as $event) {
                if (in_array($event->getId(), $registeredEvents)) continue;
                $form                 = $this->formFactory->create(new RegisterEventType(), RegisterEventCommand::create($user->get(), $event));
                $registerableEvents[] = array(
                    'form'  => $form->createView(),
                    'event' => $event
                );
            }

            // Unregisterable events
            $unregisterableEvents = array();
            foreach ($this->registrationRepository->getCancelableRegistrationsForUser($user->get()) as $registration) {
                $form                   = $this->formFactory->create(new UnregisterEventType(), UnregisterEventCommand::create($registration));
                $unregisterableEvents[] = array(
                    'form'         => $form->createView(),
                    'event'        => $registration->getEvent(),
                    'registration' => $registration,
                );
            }

            $data['user']                 = $user->get();
            $data['registerableEvents']   = $registerableEvents;
            $data['unregisterableEvents'] = $unregisterableEvents;

        }

        return $data;
    }

    /**
     * @Route("/registration/{id}", name="wemoof_unregister_event", methods={"POST", "DELETE"})
     * @Template()
     */
    public function unregisterEventAction($id)
    {
        $registration = $this->registrationRepository->getRegistration($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown registration: %d", $id)));
        if ($registration->getUser()->getId() !== $this->getUser()->getOrThrow(new AccessDeniedHttpException('Not logged in.'))->getId()) throw new AccessDeniedHttpException();
        $command = UnregisterEventCommand::create($registration);
        $form    = $this->formFactory->create(new UnregisterEventType(), $command);
        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $this->commandBus->handle($command);
                $this->addMessage("Du wurdest erfolgreich abgemeldet.");
            }
        }

        return new RedirectResponse($this->router->generate('wemoof_index'));
    }

    /**
     * @Route("/talk/{slug}/{id}", name="wemoof_talk")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function talkAction($slug, $id)
    {
        $talk = $this->talkRepository->getTalk($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown talk: %d", $id)));
        return array(
            'event' => $talk->getEvent(),
            'talk'  => $talk,
        );

    }

    /**
     * @Route("/speaker/{slug}/{id}", name="wemoof_speaker")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function speakerAction($slug, $id)
    {
        return $this->forward('wemoof.web.controller.web:userAction', array('id' => $id, 'slug' => $slug));
    }

    /**
     * @Route("/user/{slug}/{id}", name="wemoof_user")
     * @Template()
     * @Cache(maxAge=86400, sMaxAge=86400, public=true)
     */
    public function userAction($slug, $id)
    {
        $user = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        if (!$user->isPublic()) throw new AccessDeniedHttpException("Private profile.");
        $talks = $this->talkRepository->getTalksForUser($user);
        return array(
            'user'  => $user,
            'talks' => $talks,
        );
    }



    public function eventExecutionFailed(EventExecutionFailed $event)
    {

    }

    /**
     * @Route("/login/{id}/{key}", name="wemoof_login")
     *
     * @param $id
     * @param $key
     *
     * @return RedirectResponse
     */
    public function loginAction($id, $key)
    {
        $session = new Session();
        $session->start();
        /** @var User $user */
        $user = $this->userRepository->getUser($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown user: %d", $id)));
        if (!StringUtils::equals($user->getLoginKey(), $key)) throw new AccessDeniedHttpException("Invalid credentials.");
        if (!$user->isVerified()) {
            $this->commandBus->handle(VerifyUserCommand::create(new IdValue($user->getId())));
        }

        $this->commandBus->handle(ClearLoginKeyCommand::create(new IdValue($user->getId())));
        $session->set('user_id', $user->getId());

        return new RedirectResponse($this->router->generate('wemoof_index'));
    }
}
