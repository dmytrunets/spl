<?php
namespace App\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertRegExp('/Hello Evercoder!/', $client->getResponse()->getContent());
    }
}
