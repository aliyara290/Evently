<?php 
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\controllers\HomeController;
use App\controllers\AppController;
use App\controllers\AuthController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");

$router->get("/register", AuthController::class, "register");
$router->dispatch();
