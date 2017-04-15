<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 03/04/2017
 * Time: 09:28
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Page;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPages implements FixtureInterface
{

    private $route_names = ['home', 'debuter_ornithologie', 'mentions_legales', 'contact'];

    public function load(ObjectManager $manager)
    {
        foreach($this->route_names as $route)
        {
            $page = new Page();
            $page->setTitreRoute($route);
            $page->setTitrePage('Professeur Tit');
            $page->setDescription('Application participative gratuite visant à étudier les effets du climat, de l’urbanisation et de l’agriculture sur la biodiversité.');
            $page->setKeywords('ornithologie, association, climat, urbanisation, agriculture, biodiversité, découvrir, application, oiseaux');
            $manager->persist($page);
        }
        $manager->flush();
    }
}
