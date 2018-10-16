<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * Login-spoofing code is from: https://symfony.com/doc/2.8/testing/http_authentication.html
 */
class AddControllerTest extends WebTestCase {

    private $client = null;

    public function setUp() {
        $this->client = static::createClient();
    }

    public function testAddDataset() {
        $this->logIn();

        $crawler = $this->client->request('GET', '/add/Dataset');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Add a new dataset")')->count() > 0);

        // add the dataset and stuff
    }


    private function logIn() {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'secured_area';

        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken('admin', null, $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }


}
