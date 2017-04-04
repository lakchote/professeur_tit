<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Entity\User;
use AppBundle\Form\ModifyPageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/admin/pages", name="admin_pages")
     */
    public function pagesAction()
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findAll();
        return $this->render('admin/pages.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/admin/pages/modify/{titreRoute}", name="admin_pages_modify")
     */
    public function modifyPagesAction(Page $page, Request $request)
    {
        $form = $this->createForm(ModifyPageType::class, $page);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', 'Vos modifications ont été enregistrées');
        }
        return $this->render('admin/modify_page.html.twig', [
            'form' => $form->createView(),
            'titreRoute' => $page->getTitreRoute()
        ]);
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
