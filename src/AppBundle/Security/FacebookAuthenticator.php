<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 31/01/2017
 * Time: 22:56
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FacebookAuthenticator extends AbstractGuardAuthenticator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {

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

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $facebookProvider = $this->container->get('app.facebook_provider');
        try {
            $accessToken = $facebookProvider->getAccessToken(
                'authorization_code',
                ['code' => $credentials]
            );
        }
        catch (IdentityProviderException $e) {
            $response = $e->getResponseBody();
            $errorCode = $response['error']['code'];
            $message = $response['error']['message'];

            throw new CustomUserMessageAuthenticationException('Il y a eu un problème lors de la connexion au compte : ' . $errorCode);
        }

        /** @var FacebookUser $facebookUser */
        $facebookUser = $facebookProvider->getResourceOwner($accessToken);
        $email = $facebookUser->getEmail();
        $prenom = $facebookUser->getFirstName();
        $nom = $facebookUser->getLastName();

        if(!$user = $this->container->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['email' => $email])) {
            $user = new User();
            $this->container->get('app.register_user')->registerUserOAuth2($user, $email,$nom,$prenom);
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();
        $encoder = $this->container->get('security.password_encoder');
        if(!$encoder->isPasswordValid($user, $plainPassword)) {
            throw new BadCredentialsException();
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function supportsRememberMe()
    {
    }

}