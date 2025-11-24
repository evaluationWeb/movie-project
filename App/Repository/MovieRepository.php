<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Model\Movie;
use App\Model\Category;

class MovieRepository
{
    //Attributs
    private \PDO $connect;

    //Constructeur
    public function __construct()
    {
        //Injection de dépendance
        $this->connect = (new Mysql())->connectBDD();
    }

    //Méthodes
    /**
     * Méthode qui ajoute un Film (Movie) en BDD
     * @param Movie $movie Film a ajouter en BDD
     * @return void
     * @throws \Exception erreur SQL
     */
    public function saveMovie(Movie $movie): void
    {
        try {
            //Ecrire la requête
            $sql = "INSERT INTO movie(title, `description`, publish_at, duration, cover)VALUE(?,?,?,?,?)";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner les paramètres
            $req->bindValue(1, $movie->getTitle(), \PDO::PARAM_STR);
            $req->bindValue(2, $movie->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $movie->getPublishAt()->format("Y-m-d"), \PDO::PARAM_STR);
            $req->bindValue(4, $movie->getDuration(), \PDO::PARAM_INT);
            $req->bindValue(5, $movie->getCover(), \PDO::PARAM_STR);
            //Exécuter la requête
            $req->execute();
            //Récupérer l'id du film ajouté
            $id = $this->connect->lastInsertId();
            //Setter l'id à l'objet Movie
            $movie->setId($id);
            //Ajout des categories (si elle existe)
            $this->saveCategoryToMovie($movie);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Méthode qui assigne les Catégories (Category) à un Film en BDD
     * @param Movie $movie Film contenant les Categories
     * @return void
     * @throws \Exception erreur SQL
     */
    public function saveCategoryToMovie(Movie $movie): void
    {
        try {
            //Boucle pour associer les categories à la table association
            foreach ($movie->getCategories() as $category) {
                //Requête table association movie_category
                $sqlAsso = "INSERT INTO movie_category(id_movie, id_category) VALUE(?,?)";
                //Préparer la requête
                $reqAsso = $this->connect->prepare($sqlAsso);
                //Assigner les paramètres
                //BindValue id Movie
                $reqAsso->bindValue(1, $movie->getId(), \PDO::PARAM_INT);
                //BindValue si objet Category(getter)
                $reqAsso->bindValue(2, $category->getId(), \PDO::PARAM_INT);
                //Exécuter la requête
                $reqAsso->execute();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Méthode qui retourne un tableau de tous les films (Movie)
     * @return array<Movie> $movies
     * @throws \Exception Erreur SQL
     */
    public function findAllMovies(): array
    {
        try {
            //Requête SQL
            $sql = "SELECT m.id, m.title, m.`description`, m.publish_at, 
            GROUP_CONCAT(c.`name`) AS categories FROM movie AS m 
            LEFT JOIN movie_category AS mv ON m.id = mv.id_movie 
            LEFT JOIN category AS c ON mv.id_category = c.id GROUP BY m.id";
            //Préparer la requête SQL
            $req = $this->connect->prepare($sql);
            //Exécuter la requête
            $req->execute();
            //Fetch de tous les enregistrements
            $movies = $req->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $movies;
    }
}
