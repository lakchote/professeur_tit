<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\ForgottenPasswordFormType;
use AppBundle\Form\Type\ModalFormType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserModalController extends Controller
{

    /**
     * @Route("/modal/login/form-desktop", name="modal_form_desktop")
     * @Method("GET")
     */
    public function getModalDesktopAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal= $this->createForm(ModalFormType::class);
            return $this->render('modal/modal_login_desktop.html.twig', [
                'form' => $modal->createView()
            ]);
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/modal/login/form-mobile", name="modal_form_mobile")
     * @Method("GET")
     */
    public function getModalMobileAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal = $this->createForm(ModalFormType::class);
            return $this->render('modal/modal_login_mobile.html.twig', [
                'form' => $modal->createView()
            ]);
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/modal/reset_password", name="modal_reset_password")
     * @Method("GET")
     */
    public function getModalResetPasswordAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal = $this->createForm(ForgottenPasswordFormType::class);
            return $this->render('modal/modal_reset_password.html.twig', [
                'form' => $modal->createView()
            ]);
        }
        return new Response('Type de requête invalide');
    }
}
