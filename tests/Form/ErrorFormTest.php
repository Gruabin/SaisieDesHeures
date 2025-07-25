<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorFormTest extends WebTestCase
{
    public function testFormulaireInvalideGenerale(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/1');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue('');
        $form['ajout_generale[centre_de_charge]']->setValue('');
        $form['ajout_generale[temps_main_oeuvre]']->setValue('');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('Veuillez sélectionner une tâche.', $content);
        $this->assertStringContainsString('Veuillez sélectionner une charge.', $content);
        $this->assertStringContainsString('Veuillez renseigner un temps.', $content);
    }

    public function testFormulaireInvalideFabrication(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('');
        $form['ajout_fabrication[operation]']->setValue('');
        $form['ajout_fabrication[tacheSpecifique]']->setValue('');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue('');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('Veuillez saisir la partie numérique.', $content);
        $this->assertStringContainsString('Veuillez saisir une opération.', $content);
        $this->assertStringContainsString('Veuillez renseigner un temps.', $content);
    }

    public function testFormulaireInvalideService(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/3');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('');
        $form['ajout_service[operation]']->setValue('');
        $form['ajout_service[temps_main_oeuvre]']->setValue('');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('Veuillez saisir la partie numérique.', $content);
        $this->assertStringContainsString('Veuillez saisir une opération.', $content);
        $this->assertStringContainsString('Veuillez renseigner un temps.', $content);
    }

    public function testFormulaireInvalideProjet(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/4');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('');
        $form['ajout_projet[activite]']->setValue('');
        $form['ajout_projet[tache]']->setValue('');
        $form['ajout_projet[temps_main_oeuvre]']->setValue('');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('Veuillez saisir la partie numérique.', $content);
        $this->assertStringContainsString('Veuillez saisir une activité.', $content);
        $this->assertStringContainsString('Veuillez sélectionner une tâche.', $content);
        $this->assertStringContainsString('Veuillez renseigner un temps.', $content);
    }

    public function testFormulaireExcesHeures(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/4');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('LV11111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue('13');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('Le temps doit être compris entre 0.1 et 12 heures.', $content);
    }

    public function testFormulaireExcesOrdreProjet(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/4');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('LV11111111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue('0.1');

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('La partie numérique doit contenir exactement 5 chiffres.', $content);
    }

    public function testFormulaireExcesOrdreFabrication(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV11111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('La partie numérique doit contenir exactement 5 chiffres.', $content);
    }

    public function testFormulaireExcesOrdreService(): void
    {
        // Tester la connexion
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec l'id de l'utilisateur l'utilisateur Employe en base de donnée
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->submit($form);

        // Accès au formulaire general
        $crawler = $client->request('GET', '/chargement-formulaire/3');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('LV11111111');
        $form['ajout_service[operation]']->setValue(value: 10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Vérifier si les erreurs sont affichées
        $this->assertStringContainsString('La partie numérique doit contenir exactement 5 chiffres.', $content);
    }
}