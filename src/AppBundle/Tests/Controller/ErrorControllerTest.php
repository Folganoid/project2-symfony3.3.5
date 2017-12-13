<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest extends WebTestCase
{
    public function testError()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/error');
    }

}
