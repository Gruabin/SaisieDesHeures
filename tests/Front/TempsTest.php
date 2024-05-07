<?php

namespace App\Tests\Front;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\PantherTestCase;

class TempsTest extends PantherTestCase
{
    public function testApparitionFormulaire(): void
    {
        $client = static::createPantherClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        // Connexion
        $client->waitForEnabled('[type="submit"]');
        $form = $crawler->selectButton('Connexion')->form();
        $form['connexion[id]'] = 'LV0000001';
        $client->waitForEnabled('[type="submit"]');
        $client->submit($form);
        $client->waitFor('#temps');

        // Vérifiez que le formulaire n'est pas visible
        $this->assertSelectorIsNotVisible('#divOrdre');
        $this->assertSelectorIsNotVisible('#divActivite');
        $this->assertSelectorIsNotVisible('#divTache');
        $this->assertSelectorIsNotVisible('#divOperation');
        $this->assertSelectorIsNotVisible('#divTacheSpe');
        $this->assertSelectorIsNotVisible('#divCentreCharge');
        $this->assertSelectorIsNotVisible('#divSaisieTemps');

        // Vérification pour le type Générale
        $selectType = $client->findElement(WebDriverBy::id('type'));
        $selectType->findElement(WebDriverBy::xpath("//option[@value='1']"))->click();

        $this->assertSelectorIsNotVisible('#divOrdre');
        $this->assertSelectorIsNotVisible('#divActivite');
        $this->assertSelectorIsVisible('#divTache');
        $this->assertSelectorIsNotVisible('#divOperation');
        $this->assertSelectorIsNotVisible('#divTacheSpe');
        $this->assertSelectorIsVisible('#divCentreCharge');
        $this->assertSelectorIsVisible('#divSaisieTemps');

        // Vérification pour le type Fabrication
        $selectType = $client->findElement(WebDriverBy::id('type'));
        $selectType->findElement(WebDriverBy::xpath("//option[@value='2']"))->click();

        $this->assertSelectorIsVisible('#divOrdre');
        $this->assertSelectorIsNotVisible('#divActivite');
        $this->assertSelectorIsNotVisible('#divTache');
        $this->assertSelectorIsVisible('#divOperation');
        $this->assertSelectorIsVisible('#divTacheSpe');
        $this->assertSelectorIsNotVisible('#divCentreCharge');
        $this->assertSelectorIsVisible('#divSaisieTemps');

        // Vérification pour le type Service
        $selectType = $client->findElement(WebDriverBy::id('type'));
        $selectType->findElement(WebDriverBy::xpath("//option[@value='3']"))->click();

        $this->assertSelectorIsVisible('#divOrdre');
        $this->assertSelectorIsNotVisible('#divActivite');
        $this->assertSelectorIsNotVisible('#divTache');
        $this->assertSelectorIsVisible('#divOperation');
        $this->assertSelectorIsNotVisible('#divTacheSpe');
        $this->assertSelectorIsNotVisible('#divCentreCharge');
        $this->assertSelectorIsVisible('#divSaisieTemps');

        // Vérification pour le type Projet
        $selectType = $client->findElement(WebDriverBy::id('type'));
        $selectType->findElement(WebDriverBy::xpath("//option[@value='4']"))->click();
        $this->assertSelectorIsVisible('#divOrdre');
        $this->assertSelectorIsVisible('#divActivite');
        $this->assertSelectorIsVisible('#divTache');
        $this->assertSelectorIsNotVisible('#divOperation');
        $this->assertSelectorIsNotVisible('#divTacheSpe');
        $this->assertSelectorIsNotVisible('#divCentreCharge');
        $this->assertSelectorIsVisible('#divSaisieTemps');
    }
}
