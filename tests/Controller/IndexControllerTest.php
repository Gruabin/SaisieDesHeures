<?php

namespace App\Tests\Controller;

use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TypeHeuresRepository;
use App\Repository\FavoriTypeHeureRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    public function testChangementFormulaireGenerale(): void
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

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');

        $this->assertResponseIsSuccessful();

        // Vérifier que le turbo-frame est présent avec son id
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Changer le type d'heure
        $client->request('GET', '/type-select', ['type' => 1]);
        $this->assertStringContainsString('/chargement-formulaire/1', $client->getRequest()->getUri());
        $crawler = $client->request('GET', '/chargement-formulaire/1');

        $this->assertResponseIsSuccessful();

        // Verifier que tous les champs soient bien présent
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('select[name="ajout_heures[tache]"]');
        $this->assertSelectorExists('select[name="ajout_heures[centre_de_charge]"]');
        $this->assertSelectorExists('input[name="ajout_heures[temps_main_oeuvre]"]');

        $this->assertStringContainsString('Temps main d\'oeuvre', $client->getResponse()->getContent());
    }

    public function testChangementFormulaireFabrication(): void
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

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');

        $this->assertResponseIsSuccessful();

        // Vérifier que le turbo-frame est présent avec son id
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Changer le type d'heure
        $client->request('GET', '/type-select', ['type' => 2]);
        $this->assertStringContainsString('/chargement-formulaire/2', $client->getRequest()->getUri());
        $crawler = $client->request('GET', '/chargement-formulaire/2');

        $this->assertResponseIsSuccessful();

        // Verifier que tous les champs soient bien présent
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="ajout_heures[ordre]"]');
        $this->assertSelectorExists('input[name="ajout_heures[operation]"]');
        $this->assertSelectorExists('select[name="ajout_heures[tacheSpecifique]"]');
        $this->assertSelectorExists('input[name="ajout_heures[temps_main_oeuvre]"]');

        $this->assertStringContainsString('Temps main d\'oeuvre', $client->getResponse()->getContent());
    }

    public function testChangementFormulaireService(): void
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

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');

        $this->assertResponseIsSuccessful();

        // Vérifier que le turbo-frame est présent avec son id
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Changer le type d'heure
        $client->request('GET', '/type-select', ['type' => 3]);
        $this->assertStringContainsString('/chargement-formulaire/3', $client->getRequest()->getUri());
        $crawler = $client->request('GET', '/chargement-formulaire/3');

        $this->assertResponseIsSuccessful();

        // Verifier que tous les champs soient bien présent
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="ajout_heures[ordre]"]');
        $this->assertSelectorExists('input[name="ajout_heures[operation]"]');
        $this->assertSelectorExists('input[name="ajout_heures[temps_main_oeuvre]"]');

        $this->assertStringContainsString('Temps main d\'oeuvre', $client->getResponse()->getContent());
    }

    public function testChangementFormulaireProjet(): void
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

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');

        $this->assertResponseIsSuccessful();

        // Vérifier que le turbo-frame est présent avec son id
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Changer le type d'heure
        $client->request('GET', '/type-select', ['type' => 4]);
        $this->assertStringContainsString('/chargement-formulaire/4', $client->getRequest()->getUri());
        $crawler = $client->request('GET', '/chargement-formulaire/4');

        $this->assertResponseIsSuccessful();

        // Verifier que tous les champs soient bien présent
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="ajout_heures[ordre]"]');
        $this->assertSelectorExists('select[name="ajout_heures[activite]"]');
        $this->assertSelectorExists('select[name="ajout_heures[tache]"]');
        $this->assertSelectorExists('input[name="ajout_heures[temps_main_oeuvre]"]');

        $this->assertStringContainsString('Temps main d\'oeuvre', $client->getResponse()->getContent());
    }

    public function testChangerFavoriTypeHeure()
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

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');

        $this->assertResponseIsSuccessful();

        // Vérifier que le turbo-frame est présent avec son id
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Récupérer un type d'heure existant
        $typeHeureRepo = static::getContainer()->get(TypeHeuresRepository::class);
        $typeHeure = $typeHeureRepo->findOneBy([]);
        $this->assertNotNull($typeHeure);

        // Effectuer la requête POST
        $client->request('POST', '/favori/type-heure', [
            'type_heure_id' => $typeHeure->getId(),
        ]);

        // Vérifie le code de réponse
        $this->assertResponseStatusCodeSame(204);
    }
}
