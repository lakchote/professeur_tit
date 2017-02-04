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
            $this->em->persist($user);
            $this->em->flush();
            return $user;
    }

}