<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ForgottenPasswordFormType;
use AppBundle\Form\ModalFormType;
use AppBundle\Form\NewPasswordFormType;
use AppBundle\Form\ProfilFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(ModalFormType::class);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $this->get('app.register_user')->registerUser($form);
                ($form['remember_me']->getData() == null) ?
                    $response = $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess($user, $request, $this->get('app.security.login_form_authenticator'), 'main') :
                    $response = $this->get('app.register_user')->rememberMe($user, $request);
                return $response;
            } else {
                $response = new Response();
                $response->setStatusCode(401);
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
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(ForgottenPasswordFormType::class);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('app.send_mail')->sendResetPasswordMail($form['email']->getData());
                return new Response('Un email vous a été envoyé à l\'adresse <strong>' . $form['email']->getData() . '</strong>.');
            } else {
                $response = new Response();
                $response->setStatusCode(401)->setContent($this->get('templating')->render('modal/modal_reset_password.html.twig', [
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
        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->findOneBy(['email' => $data['email']]);
            if ($user->getResetPassword() !== $data['resetPassword']) {
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

    /**
     * @Route("/manage/profil", name="manage_profil_membre")
     * @Security("is_granted('ROLE_OBSERVATEUR')")
     */
    public function profilUserAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $registerDate = $this->get('app.profil_user')->getRegisterDate($user);
        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Vos modifications ont été enregistrées');
        }
        $nbObservations = $this->get('app.profil_user')->getUserObservations($user);
        $observationsValidees = $this->get('app.profil_user')->getUserValidatedObservations($user);
        return $this->get('app.profil_user')->checkIfMobile($request) ? $this->render('user/manage_profil_mobile.html.twig', [
            'registerDate' => $registerDate,
            'form' => $form->createView(),
            'nbObservations' => $nbObservations,
            'obsValidees' => $observationsValidees
        ]) : $this->render('user/manage_profil.html.twig', [
                'registerDate' => $registerDate,
                'form' => $form->createView(),
                'nbObservations' => $nbObservations,
                'obsValidees' => $observationsValidees
        ]);
    }

    /**
     * @Route("/user/delete/profil", name="user_delete")
     * @Security("is_granted('ROLE_OBSERVATEUR')")
     */
    public function userDeleteAccountAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->get('app.profil_user')->deleteUser($user);
        return new RedirectResponse($this->get('router')->generate('home'));
    }

    /**
     * @Route("/user/delete/image", name="user_delete_image")
     * @Security("is_granted('ROLE_OBSERVATEUR')")
     */
    public function userDeleteImgAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->get('app.profil_user')->deleteUserImage($user);
        return new RedirectResponse($this->get('router')->generate('manage_profil_membre'));
    }

    /**
     * @Route("/user/profil/{slug}", name="user_public_profile")
     */
    public function userPublicProfilAction(User $user)
    {
        $nbObservations = $this->get('app.profil_user')->getUserObservations($user);
        $observationsValidees = $this->get('app.profil_user')->getUserValidatedObservations($user);
        return $this->render('user/public_profile.html.twig', [
            'user' => $user,
            'nbObservations' => $nbObservations,
            'obsValidees' => $observationsValidees
        ]);
    }
}
