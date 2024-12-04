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
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Vérification que la page console n'est pas accessible
        $client->request('GET', '/console');
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());

        // Vérification que la page temps n'est pas accessible
        $client->request('GET', '/temps');
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());

        // Vérification que la page historique n'est pas accessible
        $client->request('GET', '/historique');
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
        $this->assertRouteSame('temps');

        $client->request('GET', '/console');
        $this->assertRouteSame('temps');

        $client->request('GET', '/historique');
        $this->assertRouteSame('historique');
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
        $this->assertRouteSame('console');

        $client->request('GET', '/console');
        $this->assertRouteSame('console');

        $client->request('GET', '/historique');
        $this->assertRouteSame('historique');

        $client->request('GET', '/temps');
        $this->assertRouteSame('temps');
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
        $client->request('GET', '/deconnexion');
        $this->assertResponseIsSuccessful();

        // Vérifier la redirection
        $this->assertStringEndsWith('/', $client->getRequest()->getUri());
    }
}
