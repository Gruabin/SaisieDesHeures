<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Repository\DetailHeuresRepository;

class EnregistrerTest extends WebTestCase
{
    public function testSoumissionFormulaireValideGeneralManager(): void
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/1');
        
        $this->assertRouteSame('chargement_formulaire');
        
        $this->assertResponseIsSuccessful();

        // Remplir et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(5);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideGeneralEmploye(): void
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/1');
        
        $this->assertRouteSame('chargement_formulaire');
        
        $this->assertResponseIsSuccessful();

        // Remplir et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(5);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideFabricationManager()
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
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        
        $this->assertRouteSame('chargement_formulaire');
        
        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumission du formulaire
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideFabricationEmploye()
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

        $crawler = $client->request('GET', '/console');

        $this->assertResponseIsSuccessful();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        
        $this->assertRouteSame('chargement_formulaire');
        
        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumission du formulaire
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideServiceManager()
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/3');

        $this->assertRouteSame('chargement_formulaire');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('1111111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideServiceEmploye()
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/3');

        $this->assertRouteSame('chargement_formulaire');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('1111111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideProjetManager()
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/4');

        $this->assertRouteSame('chargement_formulaire');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('1111111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testSoumissionFormulaireValideProjetEmploye()
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

        $request = $client->getRequest();

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/chargement-formulaire/4');

        $this->assertRouteSame('chargement_formulaire');

        $this->assertResponseIsSuccessful();

        // Remplir les champs
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('1111111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('chargement_formulaire');
    }

    public function testEnregistrerEtQuitterManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Aller sur la page de saisie des heures
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('chargement_formulaire');

        // Récupérer le bon bouton : "Enregistrer et quitter"
        $form = $crawler->selectButton('Enregistrer et quitter')->form();

        // Remplir le formulaire
        $form['ajout_fabrication[ordre]']->setValue('1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumettre le formulaire avec ce bouton
        $client->submit($form);

        // Vérifie qu'on est redirigé vers la bonne page
        $this->assertResponseIsSuccessful();
    }

    public function testEnregistrerEtQuitterEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Aller sur la page de saisie des heures
        $crawler = $client->request('GET', '/chargement-formulaire/2');
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('chargement_formulaire');

        // Récupérer le bon bouton : "Enregistrer et quitter"
        $form = $crawler->selectButton('Enregistrer et quitter')->form();

        // Remplir le formulaire
        $form['ajout_fabrication[ordre]']->setValue('1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumettre le formulaire avec ce bouton
        $client->submit($form);

        // Vérifie qu'on est redirigé vers la bonne page
        $this->assertResponseIsSuccessful();
    }

    public function testSauvegardeDonneesGeneralManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/1');

        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(5);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'tache' => 5,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesGeneralEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/1');

        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(5);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'tache' => 5,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesFabricationManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/2');

        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'operation' => 10,
            'tacheSpecifique' => 'AMT902',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesFabricationEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/2');

        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV1111111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('AMT902');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'operation' => 10,
            'tacheSpecifique' => 'AMT902',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesServiceManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/3');

        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('1111111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesServiceEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/3');

        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('1111111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesProjetManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/4');

        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('1111111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesProjetEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        $crawler = $client->request('GET', '/chargement-formulaire/4');

        $form = $crawler->filter('form')->form();
        $form['ajout_heures[ordre]']->setValue('1111111');
        $form['ajout_heures[activite]']->setValue('100');
        $form['ajout_heures[tache]']->setValue(5);
        $form['ajout_heures[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifie que l'enregistrement a bien eu lieu
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV1111111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
    }
}
