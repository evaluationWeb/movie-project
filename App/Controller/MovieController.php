<?php

namespace App\Controller;

use App\Model\Movie;
use App\Model\Category;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use App\Utils\Tools;
use App\Controller\AbstractController;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;

class MovieController extends AbstractController
{
    //Attributs
    private MovieRepository $movieRepository;
    private CategoryRepository $categoryRepository;
    private Validator $validator;
    //Constructeur
    public function __construct()
    {
        //Injection des dépendances
        $this->movieRepository = new MovieRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->validator = new Validator();
    }

    //Méthodes
    /**
     * Méthode pour ajouter un film (Movie)
     * @return mixed Retourne le template
     */
    public function addMovie()
    {
        //Tableau avec les messages pour la vue
        $data = [];
        //Tester si le formulaire est soumis
        if (isset($_POST["submit"])) {
            //Try (éxécution de tout le code dés que le formulaire est submit)
            try {
                //Test les champs obligatoires sont renseignés
                if (!empty($_POST["publish_at"])) {

                    //Nettoyer les entrées utilsiateur ($_POST du formulaire)
                    $title = Tools::sanitize($_POST["title"]);
                    $description = Tools::sanitize($_POST["description"]);
                    $publishAt = Tools::sanitize($_POST["publish_at"]);
                    //Tester et initialiser la durée du film
                    $duration = $_POST["duration"] != "" ? (int) Tools::sanitize($_POST["duration"]) : 90;
                    //Créer un objet Movie
                    $movie = new Movie();
                    //Setter les valeurs
                    $movie->setTitle($title);
                    $movie->setDescription($description);
                    $movie->setPublishAt(new \DateTimeImmutable($publishAt));
                    $movie->setDuration($duration);
                    //Appel de la méthode de validation (validate)
                    $this->validator->validate($movie);
                    //Test si les categories existes
                    if (isset($_POST["categories"])) {
                        //Setter les categories à $movie
                        foreach ($_POST["categories"] as $category) {
                            //Créer un objet Category
                            $newCategory = new Category("");
                            //Setter l'ID
                            $newCategory->setId((int) $category);
                            //Ajouter la categorie à la liste des Category de Movie
                            $movie->addCategory($newCategory);
                        }
                    }
                    //tester si une image est importée
                    if (isset($_FILES["cover"]) && !empty($_FILES["cover"]["tmp_name"])) {
                        //Récupération du nom du fichier
                        $newname = $this->uploadFile("cover", $movie->getTitle(), ["png", "jpeg", "bmp"]);
                        //Test du format de fichier
                        if ($newname === false) {
                            $data["error"] = "Le format de fichier est invalide";
                            //setter l'iamge par défault
                            $movie->setCover("profil.png");
                        } else {
                            $data["valid"] = "Le fichier : " .  $newname . " a été importé";
                            //Set la bonne image 
                            $movie->setCover($newname);
                        }
                    }
                    //Si l'image n'existe pas 
                    else {
                        //Setter l'image par default
                        $movie->setCover("profil.png");
                    }
                    //Appeler la méthode saveMovie du MovieRepository
                    $this->movieRepository->saveMovie($movie);
                    //Message de validation
                    $data["valid"] = "Le film : " . $movie->getTitle() . " a été ajouté en BDD";
                }
                //Afficher un message d'erreur
                else {
                    $data["error"] = "Veuillez renseigner les champs du formulaire";
                }
            } catch (ValidationException $ve) {
                $data["error"] = $ve->getMessage();
            }
        }
        //Récupération des catégories(pour la vue)
        $categories = $this->categoryRepository->findAllCategories();
        //Ajout des Categories au tableau $data
        $data["categories"] = $categories;
        //Afficher la vue
        return $this->render("add_movie", "Add Category", $data);
    }

    /**
     * Méthode pour afficher tous les films  
     * @return mixed Retourne le template 
     */
    public function showAllMovies()
    {
        //Récupération de la liste des films
        $movies = $this->movieRepository->findAllMovies();
        $data = [];
        $data["movies"] = $movies;
        return $this->render("all_movies", "Liste des Films", $data);
    }
}
