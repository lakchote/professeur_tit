<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAdminHomeWhenNotLoggedIn()
    {
        $this->client->request('GET', '/admin/home');
        $this->assertContains('Redirecting to <a href="/">/</a>.', $this->client->getResponse()->getContent());
    }

    public function testAdminHomeWhenLoggedIn()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/admin/home');
        $this->assertContains('Administration', $crawler->filter('h1')->text());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $token = new UsernamePasswordToken('admin@proftit.com', 'f1x7ur3', $firewall, ["ROLE_ADMIN"]);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
