<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObsFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationController extends Controller
{

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Route("/observation", name="observation")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();


        $nbObservations = $this->get('app.profil_user')->getUserObservations($user);
        $mesObservations = $this->get('app.profil_user')->getUserObservationsFlow($user);

        return $this->render('default/obs.html.twig', [
            'mesObservations' => $mesObservations,
            'nbObservations' => $nbObservations,
        ]);

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

    /**
     * @Route("/publish", name="obs_publish")
     */

    public function obsPublishAction(Request $request)
    {
        $observation = new Observation();
        $modal= $this->createForm(ObsFormType::class ,$observation);
        $modal->handleRequest($request);
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $observation->setUser($currentUser);
        if ($request->isXmlHttpRequest()) {
            if ($modal->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($observation);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );

                $response = new Response();
                $response->setStatusCode(200)->setContent($this->renderView('default/obs.html.twig'));
                return $response;
            } else {
                $response = new Response();
                $response->setStatusCode(201)->setContent($this->renderView('modal/modal_add_obs_desktop.html.twig', ['form' => $modal->createView()]));
                return $response;

            }
        }
        return new Response('Type de requête invalide');
    }
}
