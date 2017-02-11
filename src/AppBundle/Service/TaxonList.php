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

    public function createList()
    {
        $repository = $this->em->getRepository('AppBundle:Taxon');
        $laListe = $repository->populateMyList();

        return $laListe;
    }

}

