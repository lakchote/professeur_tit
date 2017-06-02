<?php

namespace AppBundle\Security;


use AppBundle\Service\RegisterUser;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class FacebookAuthenticator extends AbstractOAuth2
{

    public function __construct(Facebook $provider, RegisterUser $registerUser, Session $session, Router $router, EntityManager $em)
    {
        $this->provider = $provider;
        $this->registerUser = $registerUser;
        $this->session = $session;
        $this->router = $router;
        $this->em = $em;
    }

    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() != '/connect/facebook-check') {
            return null;
        }

        if($code = $request->query->get('code')) {
            return $code;
        }

        throw new CustomUserMessageAuthenticationException('Impossible d\'avoir accès à Facebook');
    }

}
