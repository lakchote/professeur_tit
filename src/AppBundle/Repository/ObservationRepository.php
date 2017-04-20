<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Observation;

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
            ->orderBy('obs.date', 'DESC')
            ->getQuery();
    }

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

    public function populateMyMapNoSpecie($date)
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->andWhere('obs.date = :date')
            ->andWhere('obs.status = :validated')
            ->setParameter('date', $date)
            ->setParameter('validated', 'validated')
            ->getQuery();
    }

    public function populateMyMapNoDate($taxon)
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->andWhere('obs.taxon = :taxon')
            ->andWhere('obs.status = :validated')
            ->setParameter('taxon', $taxon)
            ->setParameter('validated', 'validated')
            ->getQuery();
    }

    public function populateMyMapBothData($date, $taxon)
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->andWhere('obs.date = :date')
            ->andWhere('obs.taxon = :taxon')
            ->andWhere('obs.status = :validated')
            ->setParameter('date', $date)
            ->setParameter('taxon', $taxon)
            ->setParameter('validated', 'validated')
            ->getQuery();
    }

    public function populateMyMapAllData()
    {
        return $this->createQueryBuilder('obs')
            ->select('obs')
            ->andWhere('obs.status = :validated')
            ->setParameter('validated', 'validated')
            ->getQuery();
    }

    public function countPendingObservations()
    {
        return $this
            ->createQueryBuilder('obs')
            ->select('COUNT(obs)')
            ->where('obs.status = :started OR obs.status = :modified')
            ->setParameter('started', Observation::OBS_STARTED)
            ->setParameter('modified', Observation::OBS_MODIFIED)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPendingObservations()
    {
        return $this
            ->createQueryBuilder('obs')
            ->where('obs.status = :started OR obs.status = :modified')
            ->setParameter('started', Observation::OBS_STARTED)
            ->setParameter('modified', Observation::OBS_MODIFIED)
            ->getQuery()
            ->getResult();
    }
}
