<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\DefaultPage;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDefaultPageData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $defaultPage = new DefaultPage();
        $defaultPage->setTitrePage('Le titre par défaut de la page');
        $defaultPage->setDescription('Balise meta description');
        $defaultPage->setKeywords('Mots clés');
        $manager->persist($defaultPage);
        $manager->flush();
    }
}
