<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class PageListener
{

    /**
     * @var EntityManager
     */
    private $em;
    private $seoData = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->get('_route');
        $this->getPageData($route);
    }

    private function getPageData($route)
    {
        if ($page = $this->em->getRepository('AppBundle:Page')->findOneBy(['titreRoute' => $route]))
        {
            $this->seoData['titrePage'] = $page->getTitrePage();
            $this->seoData['description'] = $page->getDescription();
            $this->seoData['keywords'] = $page->getKeywords();
        }
        else
        {
            $this->setDefaultPageData();
        }
    }

    private function setDefaultPageData()
    {
        $this->seoData['titrePage'] = 'Professeur Tit';
        $this->seoData['description'] = 'Application participative gratuite visant à étudier les effets du climat, de l’urbanisation et de l’agriculture sur la biodiversité.';
        $this->seoData['keywords'] = 'ornithologie, association, climat, urbanisation, agriculture, biodiversité, découvrir, application, oiseaux';
    }

    public function getSeoData()
    {
        return $this->seoData;
    }
}
