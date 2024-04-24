<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConnexionControllerTest extends WebTestCase
{
    public function testPageInaccessibleSiPasConnecte(): void
    {
        // Tester la page de connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Vérification que la page console n'est pas accessible
        $crawler = $client->request('GET', '/console');
        // Vérifier que l'url de redirection est la page d'accueil
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());

        // Vérification que la page temps n'est pas accessible
        $crawler = $client->request('GET', '/temps');
        // Vérifier que l'url de redirection est la page d'accueil
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());

        // Vérification que la page historique n'est pas accessible
        $crawler = $client->request('GET', '/historique');
        // Vérifier que l'url de redirection est la page d'accueil
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());
    }

    public function testConnexionEmploye(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Vérifier la redirection
        $this->assertStringEndsWith('/temps', $client->getRequest()->getUri());
    }

    public function testConnexionResponsable(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur Responsable en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Vérifier la redirection
        $this->assertStringEndsWith('/console', $client->getRequest()->getUri());
    }

    public function testDeconnexion(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Vérifier la redirection
        $this->assertStringEndsWith('/temps', $client->getRequest()->getUri());

        // Tester la deconnexion
        $crawler = $client->request('GET', '/deconnexion');

        // Vérifier la redirection
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());
    }
}
