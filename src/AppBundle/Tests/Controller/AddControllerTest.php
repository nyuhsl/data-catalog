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
    private $testDatasetUid = null;

    public function setUp() {
      $this->client = static::createClient();
    }

    public function testAddDataset() {
        $this->logIn();
        $crawler = $this->client->request('GET', '/add/Dataset');
        $this->client->followRedirects(true);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Add a new dataset")')->count() > 0);
        // store the new UID so we can remove this dataset later
        $this->testDatasetUid = $crawler->filter('input#dataset_dataset_uid')->attr('value');

        // add the dataset and stuff
        $submitButton = $crawler->selectButton('dataset_save');
        $form = $submitButton->form();
        $form['dataset[title]'] = 'TEST: added by functional tests';
        $form['dataset[description]'] = 'TEST: added by functional tests';
        $form['dataset[access_instructions]'] = 'TEST: added by functional tests';
        $submittedForm = $this->client->submit($form);
        $this->assertEquals('AppBundle\Controller\AddController::ingestDataset', $this->client->getRequest()->attributes->get('_controller'));
        $this->assertTrue($submittedForm->filter('html:contains("New Dataset Added!")')->count() > 0);
    }


    private function logIn() {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'secured_area';

        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken('FAKE TEST USER', null, $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        return $this->client;
    }


}
