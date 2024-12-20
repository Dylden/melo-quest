<?php

namespace App\service;

class UniqueFilenameGenerator{

    public function generateUniqueFilename($filename, $fileExtension){

        $currentTimestamp = time();
        $nameHashed = hash('sha512', $filename);

        $fileNewName = $nameHashed . '-' . $currentTimestamp . '-' . $fileExtension;

        return $fileNewName;
    }
}