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
     * @Security("is_granted('ROLE_OBSERVATEUR')")
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
                   $response = new Response();
                   $response->setStatusCode(200);
               } else {
                   $response = new Response();
                   $response->setStatusCode(201)->setContent($this->renderView('modal/modal_add_obs_desktop.html.twig', ['form' => $modal->createView()]));
               }
        return $response;
    }

    /**
     * @Route("/obs/invalid/{id}", name="obs_invalid")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function obsInvalidAction(Observation $obs, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('', 400);
        $em = $this->getDoctrine()->getManager();
        $obs->setStatus(Observation::OBS_REFUSED);
        $obs->setImage($obs->getImage()->getFilename());
        $em->persist($obs);
        $em->flush();
        $this->addFlash('success', 'L\'observation a été refusée');
        return new Response('',200);
    }

    /**
     * @Route("/obs/valid/{id}", name="obs_valid")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function obsValidAction(Observation $obs, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('', 400);
        $em = $this->getDoctrine()->getManager();
        $obs->setStatus(Observation::OBS_VALIDATED);
        $obs->setImage($obs->getImage()->getFilename());
        $em->persist($obs);
        $em->flush();
        $this->addFlash('success', 'L\'observation a été validée');
        return new Response('',200);
    }

    /**
     * @Route("/obs/en_attente", name="obs_en_attente")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function pendingObsListAction()
    {
        $pendingObsList = $this->getDoctrine()->getRepository('AppBundle:Observation')->getPendingObservations();
        return $this->render('default/obs_en_attente.html.twig',[
            'observations' => $pendingObsList
        ]);
    }
}
