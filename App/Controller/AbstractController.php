<?php

namespace App\Controller;

use App\Utils\Tools;

abstract class AbstractController
{
    /**
     * Méthode pour rendre une vue avec un template
     * @param string $template Le nom du template à inclure
     * @param string|null $title Le titre de la page
     * @param array $data Les données à passer au template
     * @return void
     */
    protected function render(string $template, ?string $title, array $data = []): void
    {
        include __DIR__ . "/../../template/template_" . $template . ".php";
    }

    /**
     * Méthode pour enregister un fichier
     * @param string $files (nom du fichier : super globale FILES)
     * @param string $rename (nom pour renommer le fichier)
     * @param array $extensions tableau des extensions permises
     * @return string|false $newName retourner le nom du fichier ou false si extension est invalide
     */
    protected function uploadFile(string $files, string $rename, array $extensions): string|false
    {
        //chemin temp du fichier
        $tmp = $_FILES[$files]["tmp_name"];
        //nom de
        $name = $_FILES[$files]["name"];
        //Récupérer l'extension du fichier
        $ext = Tools::getFileExtension($name);
        //Boolean à false par défaut
        $newName = false;
        //vérifier si l'extension est valide
        foreach ($extensions as $extension) {
            //Test si l'extension est valide
            if ($extension === $ext) {
                //Renommer le fichier
                $newName = uniqid($rename, true) . "." . $ext;
                //Déplacer et enregister le fichier avec son nouveau nom
                move_uploaded_file($tmp, __DIR__ . "/../../public/asset/"  . $newName);
            }
        }
        
        return $newName;
    }
}
