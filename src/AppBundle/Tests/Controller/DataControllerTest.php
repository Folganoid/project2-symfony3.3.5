<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataControllerTest extends WebTestCase
{
    public function testData()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/data');
    }

}
