<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnregistrerConsoleTest extends WebTestCase
{
    // Tests de soumissions de formulaires
    public function testSoumissionFormulaireValideGeneral()
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('GA0003661');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/soumission-formulaire/1');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->selectButton('Enregistrer')->form([
            'heures[tache]' => 5,
            'heures[centre_de_charge]' => 3,
            'heures[temps_main_oeuvre]' => 2.5,
        ]);

        // Soumission du formulaire
        $form['action'] = 'continuer';
        $client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testSoumissionFormulaireValideFabrication()
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('GA0003661');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/soumission-formulaire/2');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->selectButton('Enregistrer')->form([
            'heures[ordre]' => 1111111,
            'heures[operation]' => 10,
            'heures[tacheSpe]' => 3,
            'heures[temps_main_oeuvre]' => 2.5,
        ]);

        // Soumission du formulaire
        $form['action'] = 'continuer';
        $client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testSoumissionFormulaireValideService()
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('GA0003661');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/soumission-formulaire/3');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->selectButton('Enregistrer')->form([
            'heures[ordre]' => 1111111,
            'heures[operation]' => 10,
            'heures[temps_main_oeuvre]' => 2.5,
        ]);

        // Soumission du formulaire
        $form['action'] = 'continuer';
        $client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testSoumissionFormulaireValideProjet()
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('GA0003661');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/soumission-formulaire/4');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->selectButton('Enregistrer')->form([
            'heures[ordre]' => 1111111,
            'heures[activite]' => 10,
            'heures[tache]' => 8,
            'heures[temps_main_oeuvre]' => 2.5,
        ]);

        // Soumission du formulaire
        $form['action'] = 'continuer';
        $client->submit($form);

        $this->assertResponseRedirects();
    }

    public function testEnregistrerEtQuitter()
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('GA0003661');
        $client->submit($form);

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/soumission-formulaire/4');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->selectButton('Enregistrer')->form([
            'heures[ordre]' => 1111111,
            'heures[activite]' => 10,
            'heures[tache]' => 8,
            'heures[temps_main_oeuvre]' => 2.5,
        ]);

        // Soumission du formulaire
        $form['action'] = 'quitter';
        $client->submit($form);

        $this->assertResponseRedirects();
    }
}
