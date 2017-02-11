<?php

namespace AppBundle\Controller;

use AppBundle\Form\ObsFormType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationController extends Controller
{

    /**
     * @Route("/observation", name="observation")
     */
    public function indexAction()
    {
        return $this->render('default/obs.html.twig');
    }

    /**
     * @Route("/modal/observation", name="modal_add_observation")
     */
    public function getModalDesktopAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $modal= $this->createForm(ObsFormType::class);
            return $this->render('modal/modal_add_obs_desktop.html.twig', [
                'form' => $modal->createView(),
            ]);
        }
        return new Response('Type de requête invalide');
    }

    /**
     * @Route("/getListing", name="getListing")
     */
    public function getLisitngAction(Request $request)
    {
            $listeTaxons = $this->get('app.createliste')->createList($_GET['term']);
            return  $this->json($listeTaxons);
    }
}
