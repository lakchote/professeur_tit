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
        $villes = [];
        $villes[0]['ville'] = 'Poitiers';
        $villes[0]['lng'] = 0.34037500;
        $villes[0]['lat'] = 46.58022400;
        $villes[1]['ville'] = 'Paris';
        $villes[1]['lng'] = 2.28759200;
        $villes[1]['lat'] = 48.86272500;
        $villes[2]['ville'] = 'Marseille';
        $villes[2]['lng'] = 5.36977999;
        $villes[2]['lat'] = 43.29648200;
        $villes[3]['ville'] = 'Strasbourg';
        $villes[3]['lng'] = 7.75211130;
        $villes[3]['lat'] = 48.57340529;
        $villes[4]['ville'] = 'Montpellier';
        $villes[4]['lng'] = 3.87671599;
        $villes[4]['lat'] = 43.61076900;
        $villes[5]['ville'] = 'Biarritz';
        $villes[5]['lng'] = -1.55862600;
        $villes[5]['lat'] = 43.48315190;

        $naturaliste = new User();
        $naturaliste->setEmail('naturaliste@proftit.com');
        $naturaliste->setRoles('ROLE_NATURALISTE');
        $naturaliste->setPlainPassword('f1x7ur3');
        $naturaliste->setNom('Tit');
        $naturaliste->setPrenom('Naturaliste');
        $naturaliste->setDateInscription(new \DateTime());

        for($i = 0; $i < 6; ++$i)
        {
            $obsNaturalisteValidated = new Observation();
            $obsNaturalisteValidated->setLongitude($villes[$i]['lng']);
            $obsNaturalisteValidated->setLatitude($villes[$i]['lat']);
            $obsNaturalisteValidated->setDate(new \DateTime());
            $obsNaturalisteValidated->setStatus(Observation::OBS_VALIDATED);
            $obsNaturalisteValidated->setImage('poussin.jpg');
            $obsNaturalisteValidated->setDescription('Superbe poussin');
            $obsNaturalisteValidated->setTaxon($taxon);
            $obsNaturalisteValidated->setVille($villes[$i]['ville']);
            $naturaliste->addObservations($obsNaturalisteValidated);
        }

        $obsNaturalisteRefused = new Observation();
        $obsNaturalisteRefused->setLongitude('0.34037500');
        $obsNaturalisteRefused->setLatitude('46.58022400');
        $obsNaturalisteRefused->setDate(new \DateTime());
        $obsNaturalisteRefused->setStatus(Observation::OBS_REFUSED);
        $obsNaturalisteRefused->setImage('poussin.jpg');
        $obsNaturalisteRefused->setDescription('Observation refusée');
        $obsNaturalisteRefused->setTaxon($taxon);
        $obsNaturalisteRefused->setVille('Poitiers');

        $obsNaturalisteStarted = new Observation();
        $obsNaturalisteStarted->setLongitude('0.34037500');
        $obsNaturalisteStarted->setLatitude('46.58022400');
        $obsNaturalisteStarted->setDate(new \DateTime());
        $obsNaturalisteStarted->setStatus(Observation::OBS_STARTED);
        $obsNaturalisteStarted->setImage('poussin.jpg');
        $obsNaturalisteStarted->setDescription('Observation commencée');
        $obsNaturalisteStarted->setTaxon($taxon);
        $obsNaturalisteStarted->setVille('Poitiers');

        $naturaliste->addObservations($obsNaturalisteRefused);
        $naturaliste->addObservations($obsNaturalisteStarted);

        $frozen = new User();
        $frozen->setEmail('frozen@proftit.com');
        $frozen->setRoles('ROLE_FROZEN');
        $frozen->setPlainPassword('f1x7ur3');
        $frozen->setNom('Tit');
        $frozen->setPrenom('Frozen');
        $frozen->setDateBan(new \DateTime('29-05-2017'));
        $frozen->setRaisonBan('Comportement ne respectant pas la charte de bonne conduite.');
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
