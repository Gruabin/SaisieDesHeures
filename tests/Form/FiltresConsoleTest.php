<?php

namespace App\Tests\Form;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

class FiltresConsoleTest extends PantherTestCase
{
    public function testFiltreDate(): void
    {
        // // Tester la connexion
        // $client = static::createClient();
        // $client->followRedirects(true);
        // $crawler = $client->request('GET', '/_connexion');

        // $this->assertResponseIsSuccessful();

        // // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        // $form = $crawler->selectButton('Connexion')->form();
        // $form['connexion[id]']->setValue('LV0000002');
        // $client->submit($form);

        // $crawler = $client->request('GET', '/console');

        // $this->assertResponseIsSuccessful();

        // // Récupérer le formulaire
        // $form = $crawler->selectButton('Appliquer la date')->form([]);

        // // Remplir le formulaire
        // $form['filtre_date[date]']->setValue('19-04-2024');

        // // Soumettre le formulaire
        // $client->submit($form);

        // // Vérifier la redirection
        // $client->followRedirects();
        // $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $this->assertRouteSame('console');
        // $client->followRedirects();

        // // vérification de l'affichage des données
        // $this->assertSelectorTextContains('div[name="dateLigne"]', '19-04-2024');
        // $this->assertSelectorTextNotContains('div[name="dateLigne"]', '22-04-2024');
    }

    public function testFiltreToutesDate(): void
    {
        // $client = static::createPantherClient();
        // $client->followRedirects(true);
        // $crawler = $client->request('GET', '/');

        // // Connexion
        // $client->waitForEnabled('[type="submit"]');
        // $form = $crawler->selectButton('Connexion')->form();
        // $form['connexion[id]'] = 'LV0000002';
        // $client->waitForEnabled('[type="submit"]');
        // $client->submit($form);
        // $client->waitFor('#console');

        // $this->assertResponseIsSuccessful();

        // // Récupérer le formulaire
        // $client->findElement(WebDriverBy::id('check-all'))->click();
        // $client->findElement(WebDriverBy::id('filtre_responsable_button'))->click();

        // // Vérifier la redirection
        // $client->followRedirects();
        // $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $this->assertRouteSame('console');
        // $client->waitFor('#console');

        // // vérification de l'affichage des données
        // $this->assertSelectorTextContains('div[name="dateLigne"]', '22-04-2024' || '19-04-2024');
    }
}
