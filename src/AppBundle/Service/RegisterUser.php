<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 04/02/2017
 * Time: 14:11
 */

namespace AppBundle\Service;


<<<<<<< HEAD
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Symfony\Component\Form\Form;

class RegisterUser
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
=======
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;

class RegisterUser
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
>>>>>>> origin/user
    }

    public function registerUser(Form $form)
    {
            $user = new User();
<<<<<<< HEAD
=======
            $em = $this->container->get('doctrine.orm.default_entity_manager');
>>>>>>> origin/user
            $user->setNom($form['register']['nom']->getData());
            $user->setPrenom($form['register']['prenom']->getData());
            $user->setEmail($form['register']['email']->getData());
            $user->setPlainPassword($form['register']['plainPassword']->getData());
            if($form['naturaliste']->getData() == 1) {
                $user->setRoles('ROLE_PENDING_NATURALISTE');
            }
            else if ($form['newsletter']->getData() == 1) {
                $this->subscribeToMailChimp($form['register']['email']->getData());
            }
<<<<<<< HEAD
            $this->em->persist($user);
            $this->em->flush();
            return $user;
    }

=======
            $em->persist($user);
            $em->flush();
            return $user;
    }

    public function rememberMe(User $user, Request $request)
    {
        $secretKey = $this->container->getParameter('secret');
        $token = new PostAuthenticationGuardToken($user, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $userProvider = new EntityUserProvider($this->container->get('doctrine'), User::class, 'email');
        $rememberMeService = new TokenBasedRememberMeServices(array($userProvider), $secretKey, 'main', [
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

>>>>>>> origin/user
    public function subscribeToMailChimp($userMail) {
        $url = curl_init('https://us14.api.mailchimp.com/2.0/lists/subscribe.json?apikey=9d1b0a206258b3dc70c7090fb792c89c-us14&id=0e2fad9074&email[email]='
            . $userMail . '&double_optin=false&send_welcome=false');
        curl_exec($url);
        return true;
    }

}