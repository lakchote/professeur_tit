<?php
/**
 * Created by PhpStorm.
 * User: BENY
 * Date: 08/03/2017
 * Time: 19:48
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ObsManage
{
    private $em;
    private $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function deleteObs($id)
    {
        $this->em->remove($id);
        $this->em->flush();
        $this->tokenStorage->setToken(null);
    }
}
