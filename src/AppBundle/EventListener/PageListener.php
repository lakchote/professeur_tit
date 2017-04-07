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
            $this->getDefaultSEOData();
        }
    }

    public function getSeoData()
    {
        return $this->seoData;
    }

    public function getDefaultSEOData()
    {
        $defaultPage = $this->em->getRepository('AppBundle:DefaultPage')->findOneBy(['id' => 1]);
        $this->seoData['titrePage'] = $defaultPage->getTitrePage();
        $this->seoData['description'] = $defaultPage->getDescription();
        $this->seoData['keywords'] = $defaultPage->getKeywords();
    }

    public function changeDefaultPageData($titrePage, $description, $keywords)
    {
        $defaultPage = $this->em->getRepository('AppBundle:DefaultPage')->findOneBy(['id' => 1]);
        $defaultPage->setTitrePage($titrePage);
        $defaultPage->setDescription($description);
        $defaultPage->setKeywords($keywords);
    }
}
