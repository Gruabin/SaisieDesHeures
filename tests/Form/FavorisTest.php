<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FavorisTest extends WebTestCase
{
    public function testConnecterSansFavoris(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();
        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000003');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/0');

        $this->assertRouteSame('chargement_formulaire');
        
        $this->assertResponseIsSuccessful();
    }
}