<?php

namespace tests\AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testManageProfilePageWhenNotLoggedIn()
    {
        $this->client->request('GET', '/manage/profil');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testManageProfilePageDataWhenLoggedIn()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/manage/profil');
        $this->assertContains('Mon profil', $crawler->filter('h1')->text());
        $this->assertContains('3 observations', $this->client->getResponse()->getContent());
        $this->assertContains('<strong>1</strong>&nbsp;observation a été validée', $this->client->getResponse()->getContent());
        $this->assertContains('Vous êtes certifiés naturaliste par NAO.', $this->client->getResponse()->getContent());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $em = $this->client->getContainer()->get('doctrine');
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'naturaliste@proftit.com']);
        $firewall = 'main';
        $token = new UsernamePasswordToken($user, 'f1x7ur3', $firewall, ["ROLE_NATURALISTE", "ROLE_OBSERVATEUR"]);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
