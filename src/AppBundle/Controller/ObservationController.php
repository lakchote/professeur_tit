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
     * @Route("/observation_flow_user", name="observation_flow_user")
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $nbObservations = $this->get('app.profil_user')->getUserObservations($user);
        $mesObservations = $this->get('app.profil_user')->getUserObservationsFlow($user);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $mesObservations, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('default/obs.html.twig', [
            'mesObservations' => $mesObservations,
            'nbObservations' => $nbObservations,
            'pagination' => $pagination
        ]);

    }

    /**
     * @Route("/observation/delete/{observation}", name="obs_delete")
     *
     */
    public function obsDeleteAction(Request $request, observation $observation)
    {
        $this->get('app.manage_obs')->deleteObs($observation);
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
    public function getListingAction(Request $request)
    {
            $listeTaxons = $this->get('app.createliste')->createList($_GET['term']);
            return  $this->json($listeTaxons);
    }


    /**
     * @Route("/validate", name="obs_validate")
     */

    public function obsValidateAction(Request $request) {
        $observation = new Observation();
        $modal= $this->createForm(ObsFormType::class ,$observation);
        $modal->handleRequest($request);
        if ($modal->isValid()) {
            $response = new Response();
            $response->setStatusCode(200);
        }
        else {
            $response = new Response();
            $response->setStatusCode(201)->setContent($this->renderView('modal/modal_add_obs_desktop.html.twig', ['form' => $modal->createView()]));
        }
        return $response;
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
               if ($modal->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($observation);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Your observation was added !'
                );
                return $this->redirect( $this->generateUrl('observation_flow_user'));
            } else {
                   $this->addFlash(
                       'error',
                       'Your observation was not added try again !'
                   );
                   return $this->redirect( $this->generateUrl('observation_flow_user'));
            }
    }
}
