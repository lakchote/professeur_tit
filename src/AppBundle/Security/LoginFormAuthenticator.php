<?php

namespace AppBundle\Security;

use AppBundle\Form\Type\ModalFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    private $media;

    /**
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var UserPasswordEncoder $encoder
     */
    private $encoder;

    /**
     * @var AuthenticationUtils $authUtils
     */
    private $authUtils;

    /**
     * @var Router $router
     */
    private $router;

    /**
     * @var TwigEngine $twig
     */
    private $twig;

    public function __construct(FormFactory $formFactory, EntityManager $em, UserPasswordEncoder $encoder, AuthenticationUtils $authUtils, TwigEngine $twig, Router $router)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->encoder = $encoder;
        $this->authUtils = $authUtils;
        $this->router = $router;
        $this->twig = $twig;
    }

    public function getCredentials(Request $request)
    {
        $isLogin = $request->getPathInfo() == '/login/mobile' || $request->getPathInfo() == '/login/desktop' && $request->isMethod('POST');
        if (!$isLogin) {
            return;
        }
        $request->getPathInfo() == '/login/mobile' ? $this->media = 'Mobile' : $this->media = 'Desktop';
        $form = $this->formFactory->create(ModalFormType::class);
        $form->handleRequest($request);
        $data = [];
        $data['_username'] = $form['_username']->getData();
        $data['_password'] = $form['_password']->getData();
        $request->getSession()->set(SECURITY::LAST_USERNAME, $data['_username']);
        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if($credentials['_password'] == null) return false;
        else if ($this->encoder->isPasswordValid($user, $password)) {
            if(in_array('ROLE_FROZEN', $user->getRoles()))
            {
                throw new CustomUserMessageAuthenticationException('Vous avez été banni pour la raison suivante : ' . $user->getRaisonBan());
            }
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
      {
          if ($request->getSession() instanceof SessionInterface) {
              $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
          }
          $error = $this->authUtils->getLastAuthenticationError();
          $username = $this->authUtils->getLastUsername();
          $response = new Response();
          $response->setStatusCode(401);
          $form = $this->formFactory->create(ModalFormType::class,[
              '_username' => $username
          ]);
          $this->media == 'Desktop' ? $response->setContent($this->twig->render('modal/modal_login_desktop.html.twig', [
              'form' => $form->createView(),
              'error' => $error
          ])) : $response->setContent($this->twig->render('modal/modal_login_mobile.html.twig', [
              'form' => $form->createView(),
              'error' => $error
          ]));
          return $response;
      }

    protected function getLoginUrl()
    {
        return $this->router->generate('home');
    }

    public function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('home');
    }

}
