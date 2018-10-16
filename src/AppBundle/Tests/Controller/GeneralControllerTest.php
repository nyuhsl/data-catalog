<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GeneralControllerTest extends WebTestCase
{
    public function testIndexLoads()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Filter by")')->count() > 0);
    }


    public function testGotSolrResults()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($crawler->filter('html:contains("Sorry, this search returned no results.")')->count() > 0);
    }
}
