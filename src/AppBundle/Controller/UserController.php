<?php

namespace AppBundle\Controller;

use AppBundle\Form\ForgottenPasswordFormType;
use AppBundle\Form\ModalFormType;
use AppBundle\Form\NewPasswordFormType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $form = $this->createForm(ModalFormType::class);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $user = $this->get('app.register_user')->registerUser($form);
                ($form['remember_me']->getData() == null) ?
                $response = $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess($user, $request, $this->get('app.security.login_form_authenticator'), 'main') :
                $response = $this->get('app.register_user')->rememberMe($user, $request);
                return $response;
            }
            else
            {
                $response = new Response();
                $response->setStatusCode(201);
                $form['media']->getData() == 'Desktop' ?
                    $response->setContent($this->get('templating')->render('modal/modal_login_desktop.html.twig', ['form' => $form->createView()]))
                    : $response->setContent($this->get('templating')->render('modal/modal_login_mobile.html.twig', ['form' => $form->createView()]));
                return $response;
            }
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function resetPasswordAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $form = $this->createForm(ForgottenPasswordFormType::class);
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->get('app.send_mail')->sendResetPasswordMail($form['email']->getData());
                return new Response('Un email vous a été envoyé à l\'adresse <strong>' . $form['email']->getData() . '</strong>.');
            }
            else {
                $response = new Response();
                $response->setStatusCode(201)->setContent($this->get('templating')->render('modal/modal_reset_password.html.twig', [
                    'form' => $form->createView()
                ]));
                return $response;
            }
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/reset/new_password", name="reset_new_password_form")
     */
    public function resetNewPasswordAction(Request $request)
    {
        $form = $this->createForm(NewPasswordFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->findOneBy(['email' => $data['email']]);
            if($user->getResetPassword() !== $data['resetPassword']) {
                $error = new FormError('Identifiant incorrect.');
                $form->get('resetPassword')->addError($error);
                return $this->render('form/new_password.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            $user->setPlainPassword($data['plainPassword']);
            $user->setResetPassword(null);
            $em->persist($user);
            $em->flush();
            return $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess($user, $request, $this->get('app.security.login_form_authenticator'), 'main');
        }
        return $this->render('form/new_password.html.twig', [
            'form' => $form->createView()
        ]);
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
