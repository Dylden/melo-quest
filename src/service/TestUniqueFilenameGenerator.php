<?php

namespace App\service;

use PHPUnit\Framework\TestCase;

class TestUniqueFilenameGenerator extends TestCase{

    public function testGenerateUniqueFilename(){

        $uniqueFilenameGenerator = new UniqueFilenameGenerator();

        $uniqueFilename = $uniqueFilenameGenerator->generateUniqueFilename('hello', 'mp3');


        //php bin/phpunit src/service/TestUniqueFilenameGenerator.php
        $this->assertStringContainsString('mp3', $uniqueFilename);
        $this->assertStringContainsString(time(), $uniqueFilename);
        $this->assertStringNotContainsString('b30aea2b86cb98a4c08e09744f5aa24d629f37f38bfa266c16891aa46fb791fe60cfa2f01e82e5052450fc469be2fb698a0d4340bf616a1a799fb9ceb5032831', $uniqueFilename);

    }

}