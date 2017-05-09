<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home", options={"sitemap" = true})
     */
    public function indexAction(Request $request)
    {
        $observationsValidees = $this->getDoctrine()->getRepository('AppBundle:Observation')->findBy(['status' => Observation::OBS_VALIDATED], ['date' => 'DESC']);
        $obsEnAttente = $this->getDoctrine()->getRepository('AppBundle:Observation')->countPendingObservations();
        $paginationObsValidees = $this->get('knp_paginator')->paginate(
            $observationsValidees,
            $request->query->get('page', 1),
            5
        );
        $paginationData = $paginationObsValidees->getPaginationData();
        return $this->render('default/index.html.twig', [
            'obsValidees' => $paginationObsValidees,
            'obsAttente' => $obsEnAttente,
            'dernierePage' => $paginationData['endPage']
        ]);
    }

    /**
     * @Route("/debuter_ornithologie", name="debuter_ornithologie", options={"sitemap" = true})
     */
    public function debuterOrnithologieAction()
    {
        return $this->render('default/debuter_ornithologie.html.twig');
    }

    /**
     * @Route("/mentions_legales", name="mentions_legales", options={"sitemap" = true})
     */
    public function mentionsLegalesAction()
    {
        return $this->render('default/mentions_legales.html.twig');
    }

    /**
     * @Route("/contact", name="contact", options={"sitemap" = true})
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $data = $form->getData();
            $this->get('app.send_mail')->sendContactMail($data);
            $this->addFlash('success', 'Nous avons reçu votre mail et vous répondrons dans les plus brefs délais.');
            return new RedirectResponse($this->generateUrl('home'));
        }
        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sitemap", name="sitemap")
     */
    public function siteMapAction()
    {
        return $this->redirect('/sitemap.default.xml');
    }
}
