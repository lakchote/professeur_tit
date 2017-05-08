<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * TaxonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaxonRepository extends EntityRepository
{
    public function populateMyList($term)
    {
        $query = $this->_em->createQuery('SELECT a.id, a.nomVernaculaire, a.nomLatin FROM AppBundle:Taxon a WHERE a.nomVernaculaire LIKE :term OR a.nomLatin LIKE :term');
        $query->setParameter('term', '%'.$term.'%');
        $results = $query->getResult();
        return $results;
    }

    public function populateMySearchList($term)
    {
        return $this->createQueryBuilder('taxon')
            ->select('taxon')
            ->Where('taxon.nomVernaculaire LIKE :term OR taxon.nomLatin LIKE :term')
            ->setParameter('term', '%'. $term . '%')
            ->getQuery();
    }
}
