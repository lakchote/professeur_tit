<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 04/02/2017
 * Time: 14:11
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Symfony\Component\Form\Form;

class RegisterUser
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function registerUser(Form $form)
    {
            $user = new User();
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
            $this->em->persist($user);
            $this->em->flush();
            return $user;
    }

    public function subscribeToMailChimp($userMail) {
        $url = curl_init('https://us14.api.mailchimp.com/2.0/lists/subscribe.json?apikey=9d1b0a206258b3dc70c7090fb792c89c-us14&id=0e2fad9074&email[email]='
            . $userMail . '&double_optin=false&send_welcome=false');
        curl_exec($url);
        return true;
    }

}