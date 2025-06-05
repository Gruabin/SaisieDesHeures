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

        // vérification de l'affichage des données
        $this->assertSelectorExists('div[name="dateLigne"]', '22-04-2024');
        $this->assertSelectorExists('div[name="dateLigne"]', '19-04-2024');
    }

    public function testChangementFormulaireGenerale(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        
        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès à la page des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Appel AJAX simulé pour changer le type d'heure (appel à /type-select)
        $client->xmlHttpRequest('GET', '/type-select', ['type' => 1]);

        $response = $client->getResponse();

        // Vérification que c'est bien une réponse Turbo Stream
        $this->assertResponseIsSuccessful();
        $this->assertSame('text/vnd.turbo-stream.html; charset=UTF-8', $response->headers->get('Content-Type'));

        $content = $response->getContent();

        // Vérifie que le turbo-stream remplace bien le bon bloc
        $this->assertStringContainsString('<turbo-stream action="replace" target="frames-formulaire-favori">', $content);
        $this->assertStringContainsString('<turbo-stream action="replace" target="frame-favori-btn">', $content);

        // Vérifie que certains champs du formulaire sont présents dans la réponse HTML
        $this->assertStringContainsString('name="ajout_generale[tache]"', $content);
        $this->assertStringContainsString('name="ajout_generale[centre_de_charge]"', $content);
        $this->assertStringContainsString('name="ajout_generale[temps_main_oeuvre]"', $content);

        $this->assertStringContainsString('Temps main d\'oeuvre', $content);
    }

    public function testChangementFormulaireFabrication(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        
        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès à la page des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Appel AJAX simulé pour changer le type d'heure (appel à /type-select)
        $client->xmlHttpRequest('GET', '/type-select', ['type' => 2]);

        $response = $client->getResponse();

        // Vérification que c'est bien une réponse Turbo Stream
        $this->assertResponseIsSuccessful();
        $this->assertSame('text/vnd.turbo-stream.html; charset=UTF-8', $response->headers->get('Content-Type'));

        $content = $response->getContent();

        // Vérifie que le turbo-stream remplace bien le bon bloc
        $this->assertStringContainsString('<turbo-stream action="replace" target="frames-formulaire-favori">', $content);
        $this->assertStringContainsString('<turbo-stream action="replace" target="frame-favori-btn">', $content);

        // Vérifier que tous les champs sont présents dans le HTML retourné
        $this->assertStringContainsString('name="ajout_fabrication[ordre]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_fabrication[operation]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_fabrication[tacheSpecifique]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_fabrication[temps_main_oeuvre]"', $client->getResponse()->getContent());

        $this->assertStringContainsString('Temps main d\'oeuvre', $content);
    }

    public function testChangementFormulaireService(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        
        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès à la page des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Appel AJAX simulé pour changer le type d'heure (appel à /type-select)
        $client->xmlHttpRequest('GET', '/type-select', ['type' => 3]);

        $response = $client->getResponse();

        // Vérification que c'est bien une réponse Turbo Stream
        $this->assertResponseIsSuccessful();
        $this->assertSame('text/vnd.turbo-stream.html; charset=UTF-8', $response->headers->get('Content-Type'));

        $content = $response->getContent();

        // Vérifie que le turbo-stream remplace bien le bon bloc
        $this->assertStringContainsString('<turbo-stream action="replace" target="frames-formulaire-favori">', $content);
        $this->assertStringContainsString('<turbo-stream action="replace" target="frame-favori-btn">', $content);

        // Verifier que tous les champs soient bien présent
        $this->assertStringContainsString('name="ajout_service[ordre]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_service[operation]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_service[temps_main_oeuvre]"', $client->getResponse()->getContent());

        $this->assertStringContainsString('Temps main d\'oeuvre', $content);
    }

    public function testChangementFormulaireProjet(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        
        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès à la page des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Appel AJAX simulé pour changer le type d'heure (appel à /type-select)
        $client->xmlHttpRequest('GET', '/type-select', ['type' => 4]);

        $response = $client->getResponse();

        // Vérification que c'est bien une réponse Turbo Stream
        $this->assertResponseIsSuccessful();
        $this->assertSame('text/vnd.turbo-stream.html; charset=UTF-8', $response->headers->get('Content-Type'));

        $content = $response->getContent();

        // Vérifie que le turbo-stream remplace bien le bon bloc
        $this->assertStringContainsString('<turbo-stream action="replace" target="frames-formulaire-favori">', $content);
        $this->assertStringContainsString('<turbo-stream action="replace" target="frame-favori-btn">', $content);

        // Verifier que tous les champs soient bien présent
        $this->assertStringContainsString('name="ajout_projet[ordre]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_projet[activite]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_projet[tache]"', $client->getResponse()->getContent());
        $this->assertStringContainsString('name="ajout_projet[temps_main_oeuvre]"', $client->getResponse()->getContent());

        $this->assertStringContainsString('Temps main d\'oeuvre', $content);
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
