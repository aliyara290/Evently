<?php 
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\HomeController;
use App\Controllers\EventController;
use App\Controllers\AppController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");
$router->dispatch();