<?php

namespace WEMOOF\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WEMOOF\WebBundle\Form\SignupType;
use WEMOOF\WebBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class WebController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new SignupType(), new User());
        $created = false;
        if ($request->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($form->getData());
                $em->flush();
                $created = true;
            }
        }

        return array(
            'form' => $form->createView(),
            'signup' => $created,
        );
    }
}
