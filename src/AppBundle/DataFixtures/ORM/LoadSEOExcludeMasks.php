<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\SEOExcludeMask;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSEOExcludeMasks implements FixtureInterface
{
    private $excludeMasks = ['connect', 'delete', 'modal', 'login', 'logout', 'register', 'reset', 'admin', 'obs_', 'PrestaSitemapBundle', 'geoJson', 'show', 'getListing', 'search', 'manage_profil_membre', 'user_public_profile', 'observation_flow_user'];

    public function load(ObjectManager $manager)
    {
        foreach($this->excludeMasks as $key => $mask)
        {
            $seoExcludeMask = new SEOExcludeMask();
            $seoExcludeMask->setMasque($mask);
            $manager->persist($seoExcludeMask);
        }
        $manager->flush();
    }
}
