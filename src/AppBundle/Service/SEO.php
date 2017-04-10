<?php

namespace AppBundle\Service;


use AppBundle\Entity\Page;
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
     * @var string
     * Route names to exclude with mask
     */
    private $excludeMask;

    public function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
        $this->excludeMask = '_';
    }

    public function getAvailableRoutes()
    {
        $this->getUnavailableRoutes();
        foreach($this->router->getRouteCollection()->all() as $routeName => $route)
        {
            if(!in_array($routeName, $this->unavailableRoutes) && strpos($routeName, $this->excludeMask))
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
}
