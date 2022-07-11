<?php

namespace App\Services;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderFichiers
{
    private $directory;
    private $fichiers_directory;

    public function __construct($fichiers_directory)
    {
        $this->directory = $fichiers_directory;
    }

    public function upload(UploadedFile $fichiers, $nom = null)
    {

        if (!$nom) {
            $nom = uniqid();
        }

        $nouveauNom = $nom . "." . $fichiers->guessExtension();

        $fichiers->move($this->directory, $nouveauNom);

        return $nouveauNom;

    }

}