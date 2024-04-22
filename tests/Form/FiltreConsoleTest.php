<?php

namespace App\Tests\Form;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Employe;

class FiltreConsoleTest extends WebTestCase
{
    public function testFiltreDate(): void
    {
        // Connexion en tant que responsable
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(Employe::class)->find('LV0000002');
        $client->loginUser($user);

        $client->followRedirects();
        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Récupérer le formulaire
        $form = $crawler->selectButton('Appliquer la date')->form(array());

        // Remplir le formulaire
        $form['filtre_date[date]']->setValue('19-04-2024');

        // Soumettre le formulaire
        $client->submit($form);
        
        // Vérifier la redirection
        $client->followRedirects();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('console');
        $client->followRedirects();

        $this->assertSelectorExists('.ligne');
        $this->assertSelectorTextContains('.date', '19-04-2024');
    }

    public function testFiltreToutesDate(): void
    {
        // Connexion en tant que responsable
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(Employe::class)->find('LV0000002');
        $client->loginUser($user);

        $client->followRedirects();
        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Récupérer le formulaire
        $form = $crawler->selectButton('Appliquer la date')->form(array());

        // Remplir le formulaire
        $form['filtre_date[date]']->setValue('-1');

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier la redirection
        $client->followRedirects();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('console');
        $client->followRedirects();

        $this->assertSelectorExists('.ligne');
        $this->assertSelectorTextContains('.date', '19-04-2024');
    }
}
