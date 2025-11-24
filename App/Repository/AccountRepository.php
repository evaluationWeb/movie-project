<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Model\Account;

class AccountRepository
{
    //Attributs     
    private \PDO $connect;

    //Constructeur
    public function __construct()
    {
        //Injection de dépendance
        $this->connect = (new Mysql())->connectBDD();
    }

    
    //Méthode
    /**
     * Méthode qui ajoute un Compte(Account) en BDD
     * @param Movie $movie Film a ajouter en BDD
     * @return void
     * @throws \Exception erreur SQL
     */
    public function saveAccount(Account $account): void
    {
        try {
            //Ecrire la requête
            $sql = "INSERT INTO account(firstname, lastname, email, `password`, id_grant)
            VALUE(?,?,?,?,?)";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner les paramètres
            $req->bindValue(1, $account->getFirstname(), \PDO::PARAM_STR);
            $req->bindValue(2, $account->getLastname(), \PDO::PARAM_STR);
            $req->bindValue(3, $account->getEmail(), \PDO::PARAM_STR);
            $req->bindValue(4, $account->getPassword(), \PDO::PARAM_STR);
            $req->bindValue(5, $account->getGrant()->getId(), \PDO::PARAM_INT);
            //Exécuter la requête
            $req->execute();
        } catch (\PDOException $e) {
            throw new \PDOException("Erreur d'enregistrement en BDD");
        }
    }
     /**
     * Méthode qui vérifie si un Compte(Account) avec un email existe en BDD
     * @param string $email email du Compte(Account)
     * @return bool true si existe / false si n'existe pas
     * @throws \Exception erreur SQL
     */
    public function isAccountExistsWithEmail(string $email): bool
    {
        try {
            //Ecrire la requête
            $sql = "SELECT id FROM account WHERE email = ?";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner le paramètre
            $req->bindParam(1, $email, \PDO::PARAM_STR);
            //Exécuter la requête
            $req->execute();
            //Fetch le resultat
            $account = $req->fetch(\PDO::FETCH_ASSOC);
            //Test si la categorie n'existe pas
            if (empty($account)) {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }
    /**
     * Méthode qui retourne  un Compte (Account) avec un email
     * @param string $email email du Compte (Account)
     * @return array|false $account array si existe / false si n'existe pas
     * @throws \Exception erreur SQL
     */
    public function findAccountByEmail(string $email): array|bool
    {
        try {
            //Ecrire la requête
            $sql = "SELECT a.id, a.firstname, a.lastname, a.email, a.`password`, g.`name` FROM account AS a 
            INNER JOIN `grant` AS g ON a.id_grant = g.id
            WHERE email = ?";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner le paramètre
            $req->bindParam(1, $email, \PDO::PARAM_STR);
            //Exécuter la requête
            $req->execute();
            //Fetch le resultat
            $account = $req->fetch(\PDO::FETCH_ASSOC);   
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        //Retour d'un tableau avec les informations du compte
        return $account;
    }
}