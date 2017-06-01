<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 01/02/2017
 * Time: 09:10
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use AppBundle\Form\Type\ModalFormType;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class GoogleAuthenticator extends AbstractGuardAuthenticator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getCredentials(Request $request)
    {
        $form = $this->container->get('form.factory')->create(ModalFormType::class);
        $form->handleRequest($request);
        $this->media = $form['media']->getData();
        if($request->getPathInfo() !== '/connect/google-check') {
            return null;
        }

        if($code = $request->query->get('code')) {
            return $code;
        }

        throw new CustomUserMessageAuthenticationException('Impossible d`avoir accès à Google');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $googleProvider = $this->container->get('app.google_provider');
        try {
            $accessToken = $googleProvider->getAccessToken(
                'authorization_code',
                ['code' => $credentials]
            );
        }
        catch (IdentityProviderException $e) {
            $response = $e->getResponseBody();
            $errorCode = $response['code'];
            $message = $response['message'];
            throw new CustomUserMessageAuthenticationException('Il y a eu un problème lors de la connexion au compte : ' . $errorCode);
        }

        /** @var GoogleUser $googleUser */
        $googleUser = $googleProvider->getResourceOwner($accessToken);
        $email = $googleUser->getEmail();
        $prenom = $googleUser->getFirstName();
        $nom = $googleUser->getLastName();

        if(!$user = $this->container->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['email' => $email])) {
            $user = new User();
            $this->container->get('app.register_user')->registerUserOAuth2($user, $email,$nom,$prenom);
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
        $this->container->get('session')->getFlashBag()->add('error', $exception->getMessage());
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function supportsRememberMe()
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

}
