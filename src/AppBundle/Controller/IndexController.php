<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
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
}
