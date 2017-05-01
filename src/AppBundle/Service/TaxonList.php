<?php
/**
 * Created by PhpStorm.
 * User: BENY
 * Date: 10/02/2017
 * Time: 20:08
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class TaxonList {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createList($term)
    {
        $repository = $this->em->getRepository('AppBundle:Taxon');
        $tags = $repository->populateMyList($term);
        $tags = array_map(function($tag) {
            return array(
                'value' => $tag['nomVernaculaire'],
                'label' => $tag['nomLatin'],
                'desc' => $tag['id']
            );
        }, $tags);
        return $tags;
    }

    public function createSearchResults($term)
    {
        $repository = $this->em->getRepository('AppBundle:Taxon');
        $listeTaxons = $repository->populateMySearchList($term);
        $listeTaxons = $listeTaxons->getResult();
        return $listeTaxons;
    }


}

