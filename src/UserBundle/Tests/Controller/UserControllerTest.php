<?php

namespace UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserinfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/v1/user/info/{uuid}');
    }

}
