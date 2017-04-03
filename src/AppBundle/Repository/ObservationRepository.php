<?php

namespace AppBundle\Repository;

class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserObservations($id)
    {
        return $this->createQueryBuilder('obs')
            ->select('COUNT(obs)')
            ->leftJoin('obs.user', 'user')
            ->andWhere('user.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUserObservationsFlow($id)
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->leftJoin('obs.user', 'user')
            ->andWhere('user.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
    }
    /*
    public function getUserObservationsFlow($id)
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->leftJoin('obs.user', 'user')
            ->andWhere('user.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getScalarResult();

    }*/

    public function getUserValidatedObservations($id)
    {
        return $this->createQueryBuilder('obs')
            ->select('COUNT(obs)')
            ->andWhere('obs.status = :validated')
            ->leftJoin('obs.user', 'user')
            ->andWhere('user.id = :id')
            ->setParameter('id', $id)
            ->setParameter('validated', 'validated')
            ->getQuery()
            ->getSingleScalarResult();
    }


}
