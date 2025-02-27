<?php
namespace App\service;

use PHPUnit\Framework\TestCase;
class UniqueFilenameGeneratorTest extends TestCase
{
    public function testGenerateUniqueFilename(){
        // Instancie la classe UniqueFilenameGenerator
        $uniqueFilenameGenerator = new UniqueFilenameGenerator();

        // Génère un nom de fichier unique avec le préfixe "hello" et l'extension "jpg"
        $uniqueFilename = $uniqueFilenameGenerator->generateUniqueFilename( 'hello', 'jpg');

        // Vérifie que le nom de fichier généré contient bien l'extension "jpg"
        $this->assertStringContainsString('jpg', $uniqueFilename);
    }
}

