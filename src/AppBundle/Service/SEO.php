<?php

namespace AppBundle\Service;


use AppBundle\Entity\Page;
use AppBundle\Entity\SEOExcludeMask;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class SEO
{

    /**
     * @var Router
     */
    private $router;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     * SEO Routes already added/unavailable
     */
    private $unavailableRoutes = [];

    /**
     * @var array
     * Routes available for SEO
     */
    private $routes = [];

    /**
     * @var array
     * Route names to exclude with masks
     */
    private $excludeMasks = [] ;

    public function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public function getAvailableRoutes()
    {
        $this->getUnavailableRoutes();
        $this->getExcludeMasks();
        foreach($this->router->getRouteCollection()->all() as $routeName => $route)
        {
            if(!in_array($routeName, $this->unavailableRoutes) && $this->checkExcludeMasks($routeName))
            {
                $this->routes[] = $routeName;
            }
        }
        return $this->routes;
    }

    private function getUnavailableRoutes()
    {
        foreach($this->em->getRepository('AppBundle:Page')->findAll() as $page)
        {
            $this->unavailableRoutes[] = $page->getTitreRoute();
        }
    }

    public function getExcludeMasks()
    {
        foreach($this->em->getRepository('AppBundle:SEOExcludeMask')->findAll() as $excludeMask)
        {
            $this->excludeMasks[] = $excludeMask->getMasque();
        }
        return array_unique($this->excludeMasks);
    }

    private function checkExcludeMasks($routeName)
    {
        foreach($this->excludeMasks as $key => $mask)
        {
           if($routeName[0] == '_' || strpos($routeName, $mask) !== false) return false;
        }
        return true;
    }

    public function addNewPage($data)
    {
        $page = new Page();
        $page->setTitreRoute($data['titreRoute']);
        $page->setTitrePage($data['seo']->getTitrePage());
        $page->setDescription($data['seo']->getDescription());
        $page->setKeywords($data['seo']->getKeywords());
        $this->em->persist($page);
        $this->em->flush();
    }

    public function deletePage(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();
    }

    public function deleteExcludeMask($mask)
    {
        $maskToRemove = $this->em->getRepository('AppBundle:SEOExcludeMask')->findOneBy(['masque' => $mask]);
        if ($maskToRemove)
        {
            $this->em->remove($maskToRemove);
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function addExcludeMask($mask)
    {
        if($mask == null) return false;
        $newExcludeMask = new SEOExcludeMask();
        $newExcludeMask->setMasque($mask);
        $this->em->persist($newExcludeMask);
        $this->em->flush();
        return true;
    }
}
