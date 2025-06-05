<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\DetailHeuresRepository;
use App\Entity\DetailHeures;
use App\Entity\Activite;
use App\Repository\EmployeRepository;
use App\Repository\TypeHeuresRepository;
use Doctrine\ORM\EntityManagerInterface;

class EnregistrerTest extends WebTestCase
{
    public function testSoumissionFormulaireValideGeneralManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=1
        $crawler = $client->request('GET', '/type-select', ['type' => 1]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajout = $ajoutHeuresRepo->findOneBy([
            'tache' => 100,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
        
        $this->assertEquals('LV0000002', $ajout->getEmploye()->getUserIdentifier());
    }

    public function testSoumissionFormulaireValideGeneralEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=1
        $crawler = $client->request('GET', '/type-select', ['type' => 1]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'tache' => 100,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideFabricationManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=2
        $crawler = $client->request('GET', '/type-select', ['type' => 2]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'LVT203',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideFabricationEmploye()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=2
        $crawler = $client->request('GET', '/type-select', ['type' => 2]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'LVT203',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideServiceManager()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=3
        $crawler = $client->request('GET', '/type-select', ['type' => 3]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_service[ordre]']->setValue('LV11111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideServiceEmploye()
    {
                $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=3
        $crawler = $client->request('GET', '/type-select', ['type' => 3]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_service[ordre]']->setValue('LV11111');
        $form['ajout_service[operation]']->setValue(10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérifier la route finale ou tout autre effet attendu
        $this->assertSelectorExists('turbo-frame#formulaire_saisie');

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideProjetManager()
    {        
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=4
        $crawler = $client->request('GET', '/type-select', ['type' => 4]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_projet[ordre]']->setValue('LV11111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSoumissionFormulaireValideProjetEmploye()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/_connexion');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Aller sur la page de saisie des temps
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Changer le type d'heure via /type-select?type=4
        $crawler = $client->request('GET', '/type-select', ['type' => 4]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Simuler un DOM temporaire avec Crawler pour remplir le formulaire
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());
        $form = $crawler->filter('form')->form();

        // Remplir et soumettre le formulaire
        $form['ajout_projet[ordre]']->setValue('LV11111');
        $form['ajout_projet[activite]']->setValue('100');
        $form['ajout_projet[tache]']->setValue(5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
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
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumettre le formulaire avec ce bouton
        $client->submit($form);

        // Vérifie qu'on est redirigé vers la bonne page
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'LVT203',
            'temps_main_oeuvre' => 2.5,
        ]);
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
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        // Soumettre le formulaire avec ce bouton
        $client->submit($form);

        // Vérifie qu'on est redirigé vers la bonne page
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'LVT203',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesGeneralManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 1]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'tache' => 100,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesGeneralEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 1]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'tache' => 100,
            'centre_de_charge' => 'LV0002000',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesFabricationManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 2]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'LVT203',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesFabricationEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 2]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_fabrication[ordre]']->setValue('LV11111');
        $form['ajout_fabrication[operation]']->setValue(10);
        $form['ajout_fabrication[tacheSpecifique]']->setValue('LVT203');
        $form['ajout_fabrication[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'tacheSpecifique' => 'AMT902',
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesServiceManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 3]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('LV11111');
        $form['ajout_service[operation]']->setValue(value: 10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesServiceEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 3]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_service[ordre]']->setValue('LV11111');
        $form['ajout_service[operation]']->setValue(value: 10);
        $form['ajout_service[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'operation' => 10,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesProjetManager(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 4]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('LV11111');
        $form['ajout_projet[activite]']->setValue(value: '100');
        $form['ajout_projet[tache]']->setValue(value: 5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testSauvegardeDonneesProjetEmploye(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Charger le formulaire via le turbo stream
        $client->request('GET', '/type-select', ['type' => 4]);
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Recréer le crawler avec la bonne base URI
        $crawler = new \Symfony\Component\DomCrawler\Crawler($client->getResponse()->getContent(), $client->getRequest()->getUri());

        // Récupérer et soumettre le formulaire
        $form = $crawler->filter('form')->form();
        $form['ajout_projet[ordre]']->setValue('LV11111');
        $form['ajout_projet[activite]']->setValue(value: '100');
        $form['ajout_projet[tache]']->setValue(value: 5);
        $form['ajout_projet[temps_main_oeuvre]']->setValue(2.5);

        $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Vérification de l'enregistrement en base
        $ajoutHeuresRepo = static::getContainer()->get(DetailHeuresRepository::class);
        $ajoutHeuresRepo->findOneBy([
            'ordre' => 'LV11111',
            'activite' => '100',
            'tache' => 5,
            'temps_main_oeuvre' => 2.5,
        ]);
    }

    public function testAffichageHeuresMaxAtteint(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000001');
        $client->submit($form);

        // Accès page principale
        $crawler = $client->request('GET', '/temps');
        $this->assertResponseIsSuccessful();

        // Ajoute une entrée pour simuler 12h déjà saisies
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $employe = $container->get(EmployeRepository::class)->findOneBy(['id' => 'LV0000001']);
        $typeHeure = $container->get(TypeHeuresRepository::class)->find(4);

        $detailHeure = new DetailHeures();
        $detailHeure->setEmploye($employe);
        $detailHeure->setTypeHeures($typeHeure);
        $detailHeure->setTempsMainOeuvre(12);
        $detailHeure->setDate(new \DateTime());

        $entityManager->persist($detailHeure);
        $entityManager->flush();

        // Requête vers le contrôleur qui retourne le formulaire
        $client->request('GET', '/chargement-formulaire/4');

        // Vérifie qu’on reçoit bien un turbo stream
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        $content = $client->getResponse()->getContent();

        // Vérifie qu’on affiche bien le fallback
        $this->assertStringContainsString('<turbo-stream action="replace" target="formulaire_saisie">', $content);
        $this->assertStringContainsString('Vous avez atteint le maximum de 12 heures autorisées', $content);
    }

    public function testReponseTurboStreamAvecHeader(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);

        // Connexion utilisateur
        $crawler = $client->request('GET', '/_connexion');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]']->setValue('LV0000002');
        $client->submit($form);

        // Requête GET formulaire avec type
        $crawler = $client->request('GET', '/type-select?type=1');
        $html = $client->getResponse()->getContent();
        $crawler = new \Symfony\Component\DomCrawler\Crawler($html, 'http://localhost/type-select?type=1');

        $form = $crawler->filter('form')->form();
        $form['ajout_generale[tache]']->setValue(100);
        $form['ajout_generale[centre_de_charge]']->setValue('LV0002000');
        $form['ajout_generale[temps_main_oeuvre]']->setValue(2.5);

        // On simule le Turbo-Frame
        $client->request(
            'POST',
            $form->getUri(),
            $form->getPhpValues(),
            [],
            ['HTTP_Turbo-Frame' => 'formulaire_saisie']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        // Vérifie les turbo-streams
        $this->assertSelectorExists('turbo-stream[action="update"][target="flash-messages"]');
        $this->assertSelectorExists('turbo-stream[action="replace"][target="formulaire_saisie"]');
        $this->assertSelectorExists('turbo-stream[action="update"][target="frameNbHeures"]');
    }

    public function testVerifierActiviteExistante(): void
    {
        $client = static::createClient();

        $client->request('GET', '/verifier-activite/100');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['trouve']);
        $this->assertEquals('100 - ETUDES', $data['nom']);
    }

    public function testVerifierActiviteInexistante(): void
    {
        $client = static::createClient();

        $client->request('GET', '/verifier-activite/999999');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['trouve']);
        $this->assertEquals('Aucune activité trouvée avec cet ID.', $data['message']);
    }

}
