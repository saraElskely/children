<?php

namespace TimeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MotherControllerTest extends WebTestCase
{
    public function testShowmychildren()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showMyChildren');
    }

    public function testAddchild()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addChild');
    }

    public function testShowmychildrentasks()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showMyChildrenTasks');
    }

}
