<?php 

namespace App\Controller;

use App\Controller\AbstractController;
use App\Utils\Tools;

class HomeController extends AbstractController
{

    /**
     * Méthode pour afficher la page d'accueil
     * @return mixed Retourne le template
     */
    public function index(): mixed
    {
        $data = [];
        //Test si le formulaire
        if (isset($_POST["submit"])) {
            //vérifier si un fichier a été envoyé
            if (isset($_FILES["fichier"])) {
                $newname = $this->uploadFile("fichier", "img", ["png", "jpeg", "bmp"]);
                if ($newname === false) {
                    $data["error"] = "Le format de fichier est invalide";
                } else {
                    $data["valid"] = "Le fichier : " .  $newname ." à été ajouté"; 
                }
            }
        }
        return $this->render("home","accueil", $data);
    }
}