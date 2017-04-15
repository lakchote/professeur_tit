<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CarteController extends Controller
{
    /**
     * @Route("/carte", name="carte")
     */
    public function debuterOrnithologieAction()
    {
        return $this->render('default/carte.html.twig');


    }
}
