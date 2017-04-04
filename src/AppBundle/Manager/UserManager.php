<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserManager
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function removeSignalement(User $user)
    {
        $user->resetRoles();
        $user->setRaisonBan(null);
        $user->setDateBan(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
