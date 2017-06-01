<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\MapFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Observation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CarteController extends Controller
{
    /**
     * @Route("/carte", name="carte", options={"sitemap" = true})
     */
    public function carteAction()
    {
            $form= $this->createForm(MapFormType::class);
            return $this->render('default/carte.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/carte_geoJson", name="carte_geoJson")
     */
    public function carteGeoJsonAction(Request $request)
    {
        $observation = new Observation();
        $form = $this->createForm(MapFormType::class, $observation);
        $form->handleRequest($request);
        $espece = $observation->getTaxon();
        $date = $observation->getDate();
        $lesObservations = $this->get('app.obs_list')->createList($date, $espece);
        $response = new JsonResponse($lesObservations);
        $response->setStatusCode(200);
        return $response;
    }
}
