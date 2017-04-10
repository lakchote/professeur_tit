<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Form\Type\Admin\AddNewPageType;
use AppBundle\Form\Type\Admin\ChangeDefaultSEODataType;
use AppBundle\Form\Type\Admin\ModifyPageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminSEOController extends Controller
{
    /**
     * @Route("/admin/seo/pages/list", name="admin_seo_pages_list")
     */
    public function pagesListAction()
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findAll();
        return $this->render('admin/SEO_pages_list.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/admin/seo/pages/modify/{titreRoute}", name="admin_seo_page_modify")
     */
    public function modifyPageAction(Page $page, Request $request)
    {
        $form = $this->createForm(ModifyPageType::class, $page);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', 'Vos modifications ont été enregistrées');
            return new RedirectResponse($this->generateUrl('admin_seo_page_modify', ['titreRoute' => $page->getTitreRoute()]));
        }
        return $this->render('admin/SEO_modify_page.html.twig', [
            'form' => $form->createView(),
            'titreRoute' => $page->getTitreRoute()
        ]);
    }

    /**
     * @Route("/admin/seo/defaultData/modify", name="admin_seo_defaultData_modify")
     */
    public function modifyDefaultSEODataAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $defaultPage = $em->getRepository('AppBundle:DefaultPage')->findOneBy(['id' => 1]);
        $form = $this->createForm(ChangeDefaultSEODataType::class, $defaultPage);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->get('app.listener.page')->changeDefaultPageData($form['titrePage']->getData(), $form['description']->getData(), $form['keywords']->getData());
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', 'Vos modifications ont été enregistrées');
            return new RedirectResponse($this->generateUrl('admin_seo_defaultData_modify'));
        }
        return $this->render('admin/SEO_modify_defaultData.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/seo/pages/add", name="admin_seo_page_add")
     */
    public function addPageAction(Request $request)
    {
        $form = $this->createForm(AddNewPageType::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->get('app.seo')->addNewPage($form->getData());
            $this->addFlash('success', 'Les données SEO ont été rajoutées');
            return new RedirectResponse($this->generateUrl('admin_seo_page_add'));
        }
        return $this->render('admin/SEO_add_new_page.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/seo/page/delete/{titreRoute}", name="admin_seo_page_delete")
     */
    public function deletePageAction(Page $page)
    {
        $this->get('app.seo')->deletePage($page);
        $this->addFlash('success', 'Les données SEO ont été supprimées');
        return new RedirectResponse($this->generateUrl('admin_seo_pages_list'));
    }
}
