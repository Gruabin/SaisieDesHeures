<?php

namespace App\Tests\Controller;

use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testConsoleResponsable(): void
    {

        // login
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(Employe::class)->find('LV0000002');
        $client->loginUser($user);

        $client->followRedirects();
        $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();
    }

    public function testConsoleEmploye(): void
    {

        // login
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(Employe::class)->find('LV0000001');
        $client->loginUser($user);

        $client->followRedirects();
        $client->request('GET', '/console');
        $client->followRedirects();


        $this->assertRouteSame('temps');
    }

    public function testConsoleNonConnecte(): void
    {
        $client = static::createClient();

        $client->followRedirects();
        $client->request('GET', '/console');
        $client->followRedirects();


        $this->assertRouteSame('home');
    }
}
