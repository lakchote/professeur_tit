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
     * @Route("/debuter-ornithologie", name="debuter_ornithologie")
     */
    public function debuterOrnithologieAction()
    {
        return $this->render('default/debuter_ornithologie.html.twig');
    }
}
