<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 04/02/2017
 * Time: 14:11
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;

class RegisterUser
{

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var ManagerRegistry $managerRegistry
     */
    private  $managerRegistry;

    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
    private $secret;

    public function __construct(EntityManager $em, ManagerRegistry $managerRegistry, TokenStorage $tokenStorage, $secret)
    {
        $this->em = $em;
        $this->managerRegistry = $managerRegistry;
        $this->tokenStorage = $tokenStorage;
        $this->secret = $secret;
    }

    public function registerUser(Form $form)
    {
            $user = new User();
            $user->setNom($form['register']['nom']->getData());
            $user->setPrenom($form['register']['prenom']->getData());
            $user->setEmail($form['register']['email']->getData());
            $user->setPlainPassword($form['register']['plainPassword']->getData());
            $user->setDateInscription(new \DateTime());
            if($form['naturaliste']->getData() == 1) {
                $user->setRoles('ROLE_PENDING_NATURALISTE');
            }
            else if ($form['newsletter']->getData() == 1) {
                $this->subscribeToMailChimp($form['register']['email']->getData());
            }

            $this->em->persist($user);
            $this->em->flush();
            return $user;
    }

    public function rememberMe(User $user, Request $request)
    {
        $token = new PostAuthenticationGuardToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $userProvider = new EntityUserProvider($this->managerRegistry, User::class, 'email');
        $rememberMeService = new TokenBasedRememberMeServices(array($userProvider), $this->secret, 'main', [
            'name' => 'MYREMEMBERME',
            'lifetime' => 604800,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'domain' => null,
            'always_remember_me' => true
        ]);
        $response = new Response('');
        $rememberMeService->loginSuccess($request, $response, $token);
        return $response;
    }

    public function subscribeToMailChimp($userMail)
    {
        $url = curl_init('https://us14.api.mailchimp.com/2.0/lists/subscribe.json?apikey=9d1b0a206258b3dc70c7090fb792c89c-us14&id=0e2fad9074&email[email]='
            . $userMail . '&double_optin=false&send_welcome=false');
        curl_exec($url);
        return true;
    }

    public function registerUserOAuth2(User $user, $email, $nom, $prenom)
    {
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setDateInscription(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

}
