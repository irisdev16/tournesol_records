<?php

namespace App\service;

class UniqueFilenameGenerator
{
    //je créé une méthode de génération de nom unique
    //j'ai passé en paramètres de cette function le nom de l'image et l'extension de l'image
    //je récupère le time du moment en seconde, le nom de l'image hashé
    //je créé une variable imageNewName qui contient un id unique, le nom hashé, le temps du moment en seconde et l'extension, tout ça concaténé


    public function generateUniqueFilename(string $imageOriginalName, string $imageExtension){

        $currentTimestamp = time();
        $nameHashed = hash('sha256', $imageOriginalName);

        $imageNewName = uniqid() . '-' . $nameHashed . '-' . $currentTimestamp . '.' . $imageExtension;

        return $imageNewName;
    }
}




