<?php
//import de de l'autoload
include 'vendor/autoload.php';
include 'env.php';

//Demarrage de session
session_start();

//récupération de l'url
$url = parse_url($_SERVER["REQUEST_URI"]);
$path = isset($url["path"]) ? $url["path"] : "/";

//Import des classes
use App\Controller\HomeController;
use App\Controller\ErrorController;
use App\Controller\CategoryController;
use App\Controller\RegisterController;
use App\Controller\MovieController;

//Instance des controllers
$homeController = new HomeController();
$errorController = new ErrorController();
$categoryController = new CategoryController();
$registerController = new RegisterController();
$movieController = new MovieController();

//Tester si les super globales Session existe
if (isset($_SESSION["connected"]) && $_SESSION["connected"] == true) {
    //test si connecté en tant que Admin
    if (isset($_SESSION["grant"]) && $_SESSION["grant"] == "ROLE_ADMIN") {
        //router connecté en tant que Admin
        switch ($path) {
            case '/':
                $homeController->index();
                break;
            case '/logout':
                $registerController->logout();
                break;
            case '/movie/add':
                $movieController->addMovie();
                break;
            case '/movies':
                $movieController->showAllMovies();
                break;
            case '/category/add':
                $categoryController->addCategory();
                break;
            case '/categories':
                $categoryController->showAllCategories();
                break;
            default:
                $errorController->error404();
                break;
        }
    } 
    //router connecté user
    else {
        switch ($path) {
            case '/':
                $homeController->index();
                break;
            case '/logout':
                $registerController->logout();
                break;
            case '/movie/add':
                $movieController->addMovie();
                break;
            case '/movies':
                $movieController->showAllMovies();
                break;
            default:
                $errorController->error404();
                break;
        }
    }
}
//router deconnecté
else {
    switch ($path) {
        case '/':
            $homeController->index();
            break;
        case '/login':
            $registerController->login();
            break;
        case '/register':
            $registerController->addAccount();
            break;
        default:
            $errorController->error404();
            break;
    }
}
