<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbSignalements = $em->getRepository('AppBundle:User')->countFrozenUsers();
        return $this->render('admin/home.html.twig', [
            'nbSignalements'  => $nbSignalements
        ]);;
    }

    /**
     * @Route("/admin/signalements/list", name="admin_signalements_list")
     */
    public function listSignalementsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $signalements = $em->getRepository('AppBundle:User')->getFrozenUsers();
        return $this->render('admin/signalements_list.html.twig', [
            'signalements' => $signalements
        ]);
    }

    /**
     * @Route("/admin/signalement/remove/{id}", name="admin_signalement_remove")
     */
    public function removeSignalementAction(User $id)
    {
        $this->get('app.manager.user')->removeSignalement($id);
        $this->addFlash('success', 'Le signalement a été supprimé.');
        return new RedirectResponse($this->generateUrl('admin_signalements_list'));
    }

    /**
     * @Route("/admin/user/remove/{id}", name="admin_user_remove")
     */
    public function removeUserAction(User $id)
    {
        $this->get('app.manager.user')->removeUser($id);
        $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        return new RedirectResponse($this->generateUrl('admin_signalements_list'));
    }
}
