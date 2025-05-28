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

    public function testConnecterAvecChoixFavori(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');
        
        $this->assertResponseIsSuccessful();
        
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000003');
        $client->submit($form);

        // Aller sur la page sans favoris
        $crawler = $client->request('GET', '/chargement-formulaire/0');
        $this->assertRouteSame('chargement_formulaire');
        $this->assertResponseIsSuccessful();

        // Sélectionner un type d'heure (par exemple, avec id 1)
        $crawler = $client->request('GET', '/type-select?type=1');
        $this->assertResponseIsSuccessful();

        // Soumettre ce type comme favori
        $client->request('POST', '/favori/type-heure', [
            'type_heure_id' => 1,
        ], [], [
            'HTTP_ACCEPT' => 'text/html',
        ]);
        $this->assertResponseStatusCodeSame(204);

        // Se déconnecter
        $client->request('GET', '/deconnexion');

        // Se reconnecter
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000003');
        $client->submit($form);

        // Vérifier que la redirection utilise le favori
        $crawler = $client->request('GET', '/chargement-formulaire/1');
        dump($crawler->selectButton('')->form());
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form select[name$="[tache]"]');
        $this->assertSelectorExists('form select[name$="[centre_de_charge]"]');
        $this->assertSelectorExists('form input[name$="[temps_main_oeuvre]"]');
    }

}