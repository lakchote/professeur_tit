<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Taxon;
use AppBundle\Form\ObsFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TaxonController extends Controller
{
    /**
     * @Route("/taxon/search", name="search")
     */
    public function searchAction(Request $request)
    {
       $listeTaxons = $this->get('app.createliste')->createSearchResults($_GET['srch-term']);

       $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listeTaxons, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('default/search.html.twig', [
            'terme' => $_GET['srch-term'],
            'listeTaxons' => $listeTaxons,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/taxon/show/{taxon}", name="show")
     */
    public function showAction(Request $request, Taxon $taxon)
    {
        $lesObservations = $this->get('app.obs_list')->createList("", $taxon);
        /*$lesObservations = json_encode($lesObservations);*/
        dump($lesObservations);
        return $this->render('default/show.html.twig', [
            'taxon' => $taxon,
            'observations' => $lesObservations,
        ]);
    }

}
