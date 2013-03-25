<?php

namespace WEMOOF\WebBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use WEMOOF\BackendBundle\Repository\EventRepositoryInterface;
use WEMOOF\BackendBundle\Repository\TalkRepositoryInterface;
use WEMOOF\BackendBundle\Repository\UserRepositoryInterface;
use WEMOOF\WebBundle\Form\SignupType;
use WEMOOF\BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route(service="wemoof.web.controller.web")
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

    public function __construct(Request $request, FormFactoryInterface $formFactory, ObjectManager $objectManager, EventRepositoryInterface $eventRepository, TalkRepositoryInterface $talkRepository, UserRepositoryInterface $userRepository, HttpKernelInterface $httpKernel)
    {
        $this->request = $request;
        $this->formFactory = $formFactory;
        $this->objectManager = $objectManager;
        $this->eventRepository = $eventRepository;
        $this->talkRepository = $talkRepository;
        $this->userRepository = $userRepository;
        $this->httpKernel = $httpKernel;
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $event = $this->eventRepository->getNextEvent()->get();
        return $this->forward('wemoof.web.controller.web:eventAction', array('id' => $event->getId()));
    }

    /**
     * @Route("/{id}", name="wemoof_event")
     * @Template()
     */
    public function eventAction($id)
    {
        $event = $this->eventRepository->getEvent($id)->getOrThrow(new NotFoundHttpException(sprintf("Unkown event: %d", $id)));
        $talks = $this->talkRepository->getTalksForEvent($event);

        $form = $this->formFactory->create(new SignupType(), new User());
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
            'talks' => $talks,
            'missing' => array_fill(0, 6 - count($talks), 1),
        );
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
}
