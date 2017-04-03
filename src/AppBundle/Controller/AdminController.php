<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Form\ModifyPageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        return $this->render('admin/home.html.twig');
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
}
