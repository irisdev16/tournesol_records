<?php

namespace App\service;

use PHPUnit\Framework\TestCase;

class UniqueFilenameGeneratorTest extends TestCase
{

    public function testGenerateUniqueFilename(){

        $uniqueFilenameGenerator = new \App\service\UniqueFilenameGenerator();
        $uniqueFilename = $uniqueFilenameGenerator->generateUniqueFilename( 'hello', 'jpg');

        $this->assertStringContainsString('jpg', $uniqueFilename);
    }
}

