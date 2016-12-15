<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testEvent()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/event');
    }

    public function testEvents()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/events');
    }

}
