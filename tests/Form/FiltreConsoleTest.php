<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FiltreConsoleTest extends WebTestCase
{
    public function testFiltreDate(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Récupérer le formulaire
        $form = $crawler->selectButton('Appliquer la date')->form([]);

        // Remplir le formulaire
        $form['filtre_date[date]']->setValue('04-06-2025');

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier la redirection
        $client->followRedirects();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('console');
        $client->followRedirects();

        // vérification de l'affichage des données
        $this->assertSelectorTextContains('div[name="dateLigne"]', '04-06-2025');
        $this->assertSelectorTextNotContains('div[name="dateLigne"]', '22-04-2024');
    }

    public function testFiltreToutesDate(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Récupérer le formulaire
        $form = $crawler->selectButton('Appliquer la date')->form([]);

        // Remplir le formulaire
        $form['filtre_date[date]']->setValue('-1');

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier la redirection
        $client->followRedirects();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('console');
        $client->followRedirects();

        // vérification de l'affichage des données
        $this->assertSelectorTextContains('div[name="dateLigne"]', '22-04-2024' || '19-04-2024');
    }
}
