<?php

namespace tests\AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
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

    public function testUsersRoles()
    {
        $frozenUsers = $this->em->getRepository(User::class)->countFrozenUsers();
        $this->assertEquals(1, $frozenUsers);
        $naturalistesEnAttente = $this->em->getRepository(User::class)->countNaturalistesEnAttente();
        $this->assertEquals(1, $naturalistesEnAttente);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }
}
