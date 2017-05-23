<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $taxon = $this->getReference('unTaxon');

        $naturaliste = new User();
        $naturaliste->setEmail('naturaliste@proftit.com');
        $naturaliste->setRoles('ROLE_NATURALISTE');
        $naturaliste->setPlainPassword('f1x7ur3');
        $naturaliste->setNom('Tit');
        $naturaliste->setPrenom('Naturaliste');
        $naturaliste->setDateInscription(new \DateTime());

        $obsNaturalisteValidated = new Observation();
        $obsNaturalisteValidated->setLongitude('0.34037500');
        $obsNaturalisteValidated->setLatitude('46.58022400');
        $obsNaturalisteValidated->setDate(new \DateTime());
        $obsNaturalisteValidated->setStatus('validated');
        $obsNaturalisteValidated->setImage('poussin.jpg');
        $obsNaturalisteValidated->setDescription('Superbe poussin');
        $obsNaturalisteValidated->setTaxon($taxon);
        $obsNaturalisteValidated->setVille('Poitiers');

        $obsNaturalisteRefused = new Observation();
        $obsNaturalisteRefused->setLongitude('0.34037500');
        $obsNaturalisteRefused->setLatitude('46.58022400');
        $obsNaturalisteRefused->setDate(new \DateTime());
        $obsNaturalisteRefused->setStatus('refused');
        $obsNaturalisteRefused->setImage('poussin.jpg');
        $obsNaturalisteRefused->setDescription('Observation refusée');
        $obsNaturalisteRefused->setTaxon($taxon);
        $obsNaturalisteRefused->setVille('Poitiers');

        $obsNaturalisteStarted = new Observation();
        $obsNaturalisteStarted->setLongitude('0.34037500');
        $obsNaturalisteStarted->setLatitude('46.58022400');
        $obsNaturalisteStarted->setDate(new \DateTime());
        $obsNaturalisteStarted->setStatus('started');
        $obsNaturalisteStarted->setImage('poussin.jpg');
        $obsNaturalisteStarted->setDescription('Observation commencée');
        $obsNaturalisteStarted->setTaxon($taxon);
        $obsNaturalisteStarted->setVille('Poitiers');

        $naturaliste->addObservations($obsNaturalisteValidated);
        $naturaliste->addObservations($obsNaturalisteRefused);
        $naturaliste->addObservations($obsNaturalisteStarted);

        $frozen = new User();
        $frozen->setEmail('frozen@proftit.com');
        $frozen->setRoles('ROLE_FROZEN');
        $frozen->setPlainPassword('f1x7ur3');
        $frozen->setNom('Tit');
        $frozen->setPrenom('Frozen');
        $frozen->setDateInscription(new \DateTime());

        $naturalisteEnAttente = new User();
        $naturalisteEnAttente->setEmail('naturaliste_en_attente@proftit.com');
        $naturalisteEnAttente->setRoles('ROLE_PENDING_NATURALISTE');
        $naturalisteEnAttente->setPlainPassword('f1x7ur3');
        $naturalisteEnAttente->setNom('Tit');
        $naturalisteEnAttente->setPrenom('Naturaliste en Attente');
        $naturalisteEnAttente->setDateInscription(new \DateTime());


        $admin = new User();
        $admin->setEmail('admin@proftit.com');
        $admin->setRoles('ROLE_ADMIN');
        $admin->setPlainPassword('f1x7ur3');
        $admin->setNom('Tit');
        $admin->setPrenom('Admin');
        $admin->setDateInscription(new \DateTime());

        $manager->persist($naturaliste);
        $manager->persist($frozen);
        $manager->persist($naturalisteEnAttente);
        $manager->persist($admin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
