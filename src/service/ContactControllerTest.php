<?php

namespace App\service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{

    public function testContactPageIsSuccessful(){

        $client = static::createClient();

        // Simuler une requête GET sur la page de contact
        $crawler = $client->request('GET', '/contact');

        // Vérifier que la réponse est de type 2xx (succès)

        $this->assertResponseIsSuccessful();

        //Vérifier que le titre de la page contient "Contact"
        $this->assertSelectorTextContains('div', 'Email');

    }

}


