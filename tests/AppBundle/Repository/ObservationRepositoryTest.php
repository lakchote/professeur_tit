<?php

namespace tests\AppBundle\Repository;


use AppBundle\Entity\Observation;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ObservationRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testUserNaturalisteObservations()
    {
        $totalObs = $this->em->getRepository(Observation::class)->getUserObservations(1);
        $this->assertEquals(3, $totalObs);
        $obsValidees = $this->em->getRepository(Observation::class)->getUserValidatedObservations(1);
        $this->assertEquals(1, $obsValidees);
        $obsEnAttente = $this->em->getRepository(Observation::class)->countPendingObservations();
        $this->assertEquals(1, $obsEnAttente);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }
}
