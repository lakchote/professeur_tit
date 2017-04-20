<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\Type\Admin\ModalObsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminObsController
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminObsController extends Controller
{
    /**
     * @Route("/admin/obs/list", name="admin_obs_list")
     */
    public function obsListAction()
    {
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findAll();
        return $this->render('admin/obs_list.html.twig', [
            'observations' => $observations
        ]);
    }

    /**
     * @Route("/admin/obs/validated/list", name="admin_obs_validated_list")
     */
    public function obsValidatedListAction()
    {
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findBy(['status' => Observation::OBS_VALIDATED]);
        return $this->render('admin/obs_list_validated.html.twig', [
            'observations' => $observations
        ]);
    }

    /**
     * @Route("/admin/obs/refused/list", name="admin_obs_refused_list")
     */
    public function obsRefusedAction()
    {
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findBy(['status' => Observation::OBS_REFUSED]);
        return $this->render('admin/obs_list_refused.html.twig', [
            'observations' => $observations
        ]);
    }

    /**
     * @Route("/admin/obs/modify/{id}", name="admin_obs_modify")
     */
    public function obsModifyAction(Observation $observation, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('', 400);
        ($observation->getImage() != '') ? $image = $observation->getImage()->getFileName() : $image = null;
        $form = $this->createForm(ModalObsType::class, $observation);
        $form->handleRequest($request);
        if($form->isValid())
        {
            ($form['image']->getData() == null) ? $observation->setImage($image) : $observation->setImage($form['image']->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($observation);
            $em->flush();
            $this->addFlash('success', 'L\'observation a été modifiée');
            return new Response('', 200);
        }
        return new Response($this->get('templating')->render('admin/modal/modify_obs.html.twig', [
            'form' => $form->createView(),
            'id' => $observation->getId()
        ]), 400);
    }

    /**
     * @Route("/admin/obs/delete/{id}", name="admin_obs_delete")
     */
    public function obsDeleteAction(Observation $obs, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('', 400);
        $em = $this->getDoctrine()->getManager();
        $em->remove($obs);
        $em->flush();
        $this->addFlash('success', 'L\'observation a été supprimée');
        return new Response('',200);
    }
}
