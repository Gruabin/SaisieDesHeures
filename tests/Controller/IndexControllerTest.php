<?php

namespace App\Tests\Controller;

use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
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

        // vérification de l'affichage des données
        $this->assertSelectorExists('div[name="dateLigne"]', '22-04-2024');
        $this->assertSelectorExists('div[name="dateLigne"]', '19-04-2024');
    }
}
