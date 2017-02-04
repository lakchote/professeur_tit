<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ModalFormType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * @Route("/modal/login/form-desktop", name="modal_form_desktop")
     */
    public function getModalDesktopAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal= $this->createForm(ModalFormType::class);
            return $this->render('modal/modal_desktop.html.twig', [
                'form' => $modal->createView()
            ]);
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/modal/login/form-mobile", name="modal_form_mobile")
     */
    public function getModalMobileAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal = $this->createForm(ModalFormType::class);
            return $this->render('modal/modal_mobile.html.twig', [
                'form' => $modal->createView()
            ]);
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $form = $this->createForm(ModalFormType::class);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $this->get('app.register_user')->registerUser($form);
                return $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess($user, $request, $this->get('app.security.login_form_authenticator'), 'main');
            } else {
                $response = new Response();
                $response->setStatusCode(201);
                $form['media']->getData() == 'Desktop' ?
                    $response->setContent($this->get('templating')->render('modal/modal_desktop.html.twig', ['form' => $form->createView()]))
                    : $response->setContent($this->get('templating')->render('modal/modal_mobile.html.twig', ['form' => $form->createView()]));
                return $response;
            }
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/login/desktop", name="login_desktop")
     */
    public function loginDesktopAction()
    {

    }

    /**
     * @Route("/login/mobile", name="login_mobile")
     */
    public function loginMobileAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}
