<?php 
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\HomeController;
use App\Controllers\AppController;
use App\Controllers\OrganizerController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");
$router->get("/organizer/dashboard", OrganizerController::class, "page");
$router->dispatch();