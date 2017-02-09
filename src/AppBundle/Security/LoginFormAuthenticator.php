<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 30/01/2017
 * Time: 11:41
 */

namespace AppBundle\Security;

use AppBundle\Form\ModalFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $container;
    private $media;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getCredentials(Request $request)
    {
        $isLogin = $request->getPathInfo() == '/login/mobile' || $request->getPathInfo() == '/login/desktop' && $request->isMethod('POST');
        if (!$isLogin) {
            return;
        }
        $request->getPathInfo() == '/login/mobile' ? $this->media = 'Mobile' : $this->media = 'Desktop';
        $form = $this->container->get('form.factory')->create(ModalFormType::class);
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
        return $this->container->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if($credentials['_password'] == null) return false;
        else if ($this->container->get('security.password_encoder')->isPasswordValid($user, $password)) {
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
      {
          if ($request->getSession() instanceof SessionInterface) {
              $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
          }
          $authUtils = $this->container->get('security.authentication_utils');
          $error = $authUtils->getLastAuthenticationError();
          $username = $authUtils->getLastUsername();
          $response = new Response();
          $response->setStatusCode(201);
          $form = $this->container->get('form.factory')->create(ModalFormType::class,[
              '_username' => $username
          ]);
          $this->media == 'Desktop' ? $response->setContent($this->container->get('templating')->render('modal/modal_login_desktop.html.twig', [
              'form' => $form->createView(),
              'error' => $error
          ])) : $response->setContent($this->container->get('templating')->render('modal/modal_login_mobile.html.twig', [
              'form' => $form->createView(),
              'error' => $error
          ]));
          return $response;
      }

    protected function getLoginUrl()
    {
        return $this->container->get('router')->generate('home');
    }

    public function getDefaultSuccessRedirectUrl()
    {
        return $this->container->get('router')->generate('home');
    }

}