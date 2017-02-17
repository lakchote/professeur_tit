<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 12/02/2017
 * Time: 17:10
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProfilUser
{
    private $em;
    private $tokenStorage;
    private $months = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
    ];

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function getRegisterDate(User $user)
    {
        $date = $user->getDateInscription();
        $singleDateValues = explode('-', $date->format('Y-m-d'));
        return $singleDateValues[2] . ' ' . $this->months[$singleDateValues[1]] . ' ' . $singleDateValues[0];
    }

    public function checkIfAccessGranted(User $user)
    {
        $currentUser = $this->tokenStorage->getToken()->getUser();
        if ($currentUser->getId() !== $user->getId()) {
            return false;
        }
        return true;
    }

    public function getUserObservations(User $user)
    {
        return $this->em->getRepository('AppBundle:Observation')->getUserObservations($user->getId());
    }

    public function getUserValidatedObservations(User $user)
    {
        return $this->em->getRepository('AppBundle:Observation')->getUserValidatedObservations($user->getId());
    }

    public function deleteUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
        $this->tokenStorage->setToken(null);
    }

    public function deleteUserImage(User $user)
    {
        $user->setImage('');
        $this->em->persist($user);
        $this->em->flush();
    }
}