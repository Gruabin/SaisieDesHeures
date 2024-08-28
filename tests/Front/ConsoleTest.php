<?php

namespace App\Tests\Front;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\PantherTestCase;

class ConsoleTest extends PantherTestCase
{
    public function testSelectionLigneAll(): void
    {
        $client = static::createPantherClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        // Connexion
        $client->waitForEnabled('[type="submit"]');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->waitForEnabled('[type="submit"]');
        $client->submit($form);
        $client->waitFor('#console');

        // Vérifie qu'aucune case n'est cochée au début
        $checkboxes = $client->findElements(WebDriverBy::name('checkbox_ligne'));

        foreach ($checkboxes as $checkbox) {
            $this->assertFalse($checkbox->isSelected());
        }

        // Coche la case principale
        $selectAllCheckbox = $client->findElement(WebDriverBy::id('select_all'));
        $selectAllCheckbox->click();

        // Vérifie que toutes les autres cases (non désactivées) sont également cochées
        foreach ($checkboxes as $checkbox) {
            if (null == $checkbox->getAttribute('disabled')) {
                $this->assertTrue($checkbox->isSelected());
            }
        }

        // Décoche la case principale
        $selectAllCheckbox->click();

        // Vérifie que toutes les autres cases (non désactivées) sont également décochées
        foreach ($checkboxes as $checkbox) {
            if (null == $checkbox->getAttribute('disabled')) {
                $this->assertFalse($checkbox->isSelected());
            }
        }
    }

    public function testSelectionLigneUser(): void
    {
        $client = static::createPantherClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        // Connexion
        $client->waitForEnabled('[type="submit"]');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->waitForEnabled('[type="submit"]');
        $client->submit($form);
        $client->waitFor('#console');

        $selectAllCheckbox = $client->findElement(WebDriverBy::name('select_user'));
        $checkboxes = $client->findElements(WebDriverBy::cssSelector('tr[data-employe="'.$selectAllCheckbox->getAttribute('data-employe').'"] input[type="checkbox"]'));

        // Vérifiez qu'aucune case n'est cochée au début
        foreach ($checkboxes as $checkbox) {
            $this->assertFalse($checkbox->isSelected());
        }

        // Coche la case principale
        $selectAllCheckbox->click();

        // Vérifie que toutes les autres cases (non désactivées) sont également cochées
        foreach ($checkboxes as $checkbox) {
            if (null == $checkbox->getAttribute('disabled')) {
                $this->assertTrue($checkbox->isSelected());
            }
        }

        // Décochez la case principale
        $selectAllCheckbox->click();

        // Vérifiez que toutes les autres cases (non désactivées) sont également décochées
        foreach ($checkboxes as $checkbox) {
            if (null == $checkbox->getAttribute('disabled')) {
                $this->assertFalse($checkbox->isSelected());
            }
        }
    }

    public function testSelectionAnomalie(): void
    {
        $client = static::createPantherClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        // Connexion
        $client->waitForEnabled('[type="submit"]');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->waitForEnabled('[type="submit"]');
        $client->submit($form);
        $client->waitFor('#console');

        $selectAnomalie = $client->findElement(WebDriverBy::id('select_anomalies'));
        $lignes = $client->findElements(WebDriverBy::className('ligne'));

        // Coche la case anomalies
        $selectAnomalie->click();

        // Vérifie qu'uniquement les lignes avec des anomalies sont affichées
        foreach ($lignes as $ligne) {
            if ('3' == $ligne->getAttribute('data-statut')) {
                $this->assertFalse($ligne->isDisplayed());
            }
            if ('2' == $ligne->getAttribute('data-statut')) {
                $this->assertTrue($ligne->isDisplayed());
            }
        }

        // Coche la case anomalies
        $selectAnomalie->click();

        // Vérifie qu'uniquement les lignes avec des anomalies sont affichées
        foreach ($lignes as $ligne) {
            $this->assertTrue($ligne->isDisplayed());
        }
    }

    public function testSelectionResponsable(): void
    {
        $client = static::createPantherClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        // Connexion
        $client->waitForEnabled('[type="submit"]');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000002';
        $client->waitForEnabled('[type="submit"]');
        $client->submit($form);
        $client->waitFor('#console');
        $employes = $client->findElements(WebDriverBy::className('tabEmploye'));

        // Vérifie que tous les employés du responsable connecté sont affichés
        foreach ($employes as $employe) {
            $this->assertTrue('LV0000002' == $employe->getAttribute('data-employe'));
        }

        // Sélection de tous les responsables
        $client->findElement(WebDriverBy::id('check-all'))->click();
        $client->findElement(WebDriverBy::id('filtre_responsable_button'))->click();
        $client->waitFor('#console');
        $employes = $client->findElements(WebDriverBy::className('tabEmploye'));

        // Vérifie que tous les responsables sont affichés
        foreach ($employes as $employe) {
            $this->assertTrue('LV0000002' == $employe->getAttribute('data-employe') || 'LV0000003');
        }
    }
}
