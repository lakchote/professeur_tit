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
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        /*
         * TODO: pagination, liens fonctionnels différentes rubriques si ROLE_NATURALISTE ou ROLE_OBSERVATEUR
         */
        $observationsValidees = $this->getDoctrine()->getRepository('AppBundle:Observation')->findBy(['status' => Observation::OBS_VALIDATED], ['date' => 'DESC']);
        $obsEnAttente = $this->getDoctrine()->getRepository('AppBundle:Observation')->countPendingObservations();
        return $this->render('default/index.html.twig', [
            'obsValidees' => $observationsValidees,
            'obsAttente' => $obsEnAttente
        ]);
    }

    /**
     * @Route("/debuter_ornithologie", name="debuter_ornithologie")
     */
    public function debuterOrnithologieAction()
    {
        return $this->render('default/debuter_ornithologie.html.twig');
    }

    /**
     * @Route("/mentions_legales", name="mentions_legales")
     */
    public function mentionsLegalesAction()
    {
        return $this->render('default/mentions_legales.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
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
}
