<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 02/06/2017
 * Time: 15:13
 */

namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Service\RegisterUser;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

abstract class AbstractOAuth2 extends AbstractGuardAuthenticator
{
    /**
     * @var Facebook|Google
     */
    protected $provider;

    /**
     * @var RegisterUser $registerUser
     */
    protected $registerUser;

    /**
     * @var Session $session
     */
    protected $session;

    /**
     * @var Router $router
     */
    protected $router;

    /**
     * @var EntityManager $em
     */
    protected $em;


    public function getCredentials(Request $request)
    {
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $accessToken = $this->provider->getAccessToken(
                'authorization_code',
                ['code' => $credentials]
            );
        }
        catch (IdentityProviderException $e) {
            $response = $e->getResponseBody();
            $message = $response['error']['message'];
            throw new CustomUserMessageAuthenticationException('Il y a eu un problème lors de la connexion au compte : ' . $message);
        }

        $owner = $this->provider->getResourceOwner($accessToken);
        $email = $owner->getEmail();
        $prenom = $owner->getFirstName();
        $nom = $owner->getLastName();

        if(!$user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $email])) {
            $user = new User();
            $this->registerUser->registerUserOAuth2($user, $email,$nom,$prenom);
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if(in_array('ROLE_FROZEN', $user->getRoles()))
        {
            throw new CustomUserMessageAuthenticationException('Vous avez été banni pour la raison suivante : ' . $user->getRaisonBan());
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->session->getFlashBag()->add('error', $exception->getMessage());
        return new RedirectResponse($this->router->generate('home'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    public function supportsRememberMe()
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }
}
