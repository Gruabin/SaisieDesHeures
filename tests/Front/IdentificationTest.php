<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IdentificationTest extends WebTestCase
{
    public function testIndexView(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Vérificarion du titre de la page de connexion
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('section h1', 'Identification');
    }

    public function testIndexFormView(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/_connexion');

        // Vérification des labels du formulaire de connexion
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('fieldset legend span.text-left', 'Code employé');
        $this->assertSelectorTextContains('fieldset legend span.text-right', 'Non identifié');

        // Vérification de la présence de l'input de matricule
        $this->assertSelectorExists('input[name="connexion[id]"]');

        // Vérification de la presence du bouton de connexion
        $this->assertSelectorTextContains('button[type="submit"]', 'Connexion');
    }
}
