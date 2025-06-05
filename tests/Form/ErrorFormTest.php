<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\DetailHeures;
use App\Entity\Employe;
use App\Entity\TypeHeures;
use App\Repository\EmployeRepository;
use App\Repository\TypeHeuresRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    public function testAffichageHeuresMaxDepasse(): void
    {
        $client = static::createClient();

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Récupérer les services
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(Employe::class)->findOneBy(['id' => 'LV0000002']);
        $type = $em->getRepository(TypeHeures::class)->findOneBy(['nom_type' => 'Générale']);
        $repo = $em->getRepository(DetailHeures::class);

        // Ajouter une saisie de 11h
        $heure = new DetailHeures();
        $heure->setEmploye($user);
        $heure->setTypeHeures($type);
        $heure->setCentreDeCharge($user->getCentreDeCharge());
        $heure->setTempsMainOeuvre(11.0);
        $heure->setDate(new \DateTime());
        $em->persist($heure);
        $em->flush();

        // Charger le formulaire
        $crawler = $client->request('GET', '/type-select', ['type' => $type->getId()]);
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.0);

        $client->submit($form, [], ['HTTP_Turbo-Frame' => 'formulaire_saisie']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('Vous avez dépassé le maximum de 12 heures autorisées.', $content);
    }

}