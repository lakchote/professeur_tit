<?php
/**
 * Created by PhpStorm.
 * User: BENY
 * Date: 10/02/2017
 * Time: 20:08
 */

namespace AppBundle\Service;

use AppBundle\Entity\Observation;
use Doctrine\ORM\EntityManager;

class ObsList {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createList($date, $espece)
    {
        $repository = $this->em->getRepository('AppBundle:Observation');
        if (($espece == "") && ($date == "")) {
            $tags = $repository->populateMyMapAllData();
        }
        elseif ($espece == "") {
            $tags = $repository->populateMyMapNoSpecie($date);
        }
        elseif ($date == "") {
            $tags = $repository->populateMyMapNoDate($espece);
        }
        else {
            $tags = $repository->populateMyMapBothData($date, $espece);
        }

        $tags = $tags->getResult();

        $tags = array_map(function(Observation $row) {
            $auteur = $row->getUser()->getNom() . ' '  . $row->getUser()->getPrenom();
            if ($row->getImage()) {
                $image = $row->getImage()->getFileName();
            }
            else {
                $image = "";
            }
            return array(
                'id' => $row->getId(),
                'longitude' => $row->getLongitude(),
                'latitude' => $row->getLatitude(),
                'date' => $row->getDate(),
                'image' => $image,
                'taxon' => $row->getTaxon()->getNomVernaculaire(),
                'userSlug' => $row->getUser()->getSlug(),
                'auteur' => $auteur
            );
        }, $tags);
        return $tags;
    }

}

